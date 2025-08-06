<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "db_banknagari";
$conn = new mysqli($host, $user, $password, $db);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['export'])) {
    require_once __DIR__ . '/vendor/autoload.php';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $header = ['ID Pegawai', 'Tanggal Input', 'Nama Owner', 'Alamat Owner', 'Asal Owner', 'No HP', 'Nama Usaha', 'Sektor Usaha', 'Alamat Usaha'];
    $sheet->fromArray($header, NULL, 'A1');

    $filter = isset($_GET['sektor']) && $_GET['sektor'] !== 'all' ? "WHERE sektor_usaha = '" . $conn->real_escape_string($_GET['sektor']) . "'" : "";

    $query = "SELECT id, tanggal_input, nama_owner, alamat_owner, asal_owner, handphone, nama_usaha, sektor_usaha, alamat_usaha FROM usaha_diaspora $filter";
    $result = $conn->query($query);

    if (!$result) die("Query gagal: " . $conn->error);

    $rowData = [];
    while ($row = $result->fetch_assoc()) {
        $rowData[] = array_values($row);
    }

    $sheet->fromArray($rowData, NULL, 'A2');

    $filename = 'laporan_usaha_diaspora.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$filter = isset($_GET['sektor']) && $_GET['sektor'] !== 'all' ? "WHERE sektor_usaha = '" . $conn->real_escape_string($_GET['sektor']) . "'" : "";
$query = "SELECT * FROM usaha_diaspora $filter ORDER BY tanggal_input DESC";
$result = $conn->query($query);

$countQuery = "SELECT sektor_usaha, COUNT(*) as total FROM usaha_diaspora GROUP BY sektor_usaha";
$countResult = $conn->query($countQuery);

$counts = ['Total' => 0];
while ($row = $countResult->fetch_assoc()) {
    $counts[$row['sektor_usaha']] = $row['total'];
    $counts['Total'] += $row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Usaha</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f6f8;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .cards {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .card {
            flex: 1;
            min-width: 150px;
            background: #1e293b;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .card h3 {
            margin: 0;
            font-size: 18px;
        }
        .card p {
            margin: 5px 0 0;
            font-size: 24px;
            font-weight: bold;
        }
        .filter {
            margin-bottom: 20px;
        }
        .filter select, .filter button {
            padding: 8px 12px;
            margin-right: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            font-size: 13px;
        }
        th {
            background: #0e4b6c;
            color: white;
        }
        .btn-export {
            padding: 10px 15px;
            background: #0e4b6c;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .btn-export:hover {
            background: #0b3e5a;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Laporan Data Usaha Diaspora</h2>

    <!-- <div class="cards">
        <div class="card"><h3>Total Data</h3><p><?= $counts['Total'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Kuliner</h3><p><?= $counts['Kuliner'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Fashion</h3><p><?= $counts['Fashion'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Pertanian</h3><p><?= $counts['Pertanian'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Jasa</h3><p><?= $counts['Jasa'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Teknologi</h3><p><?= $counts['Teknologi'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Perdagangan</h3><p><?= $counts['Perdagangan'] ?? 0 ?></p></div>
        <div class="card"><h3>Sektor Lainnya</h3><p><?= $counts['Lainnya'] ?? 0 ?></p></div>
    </div> -->

    <div class="filter">
        <form method="GET">
            <select name="sektor">
                <option value="all" <?= !isset($_GET['sektor']) || $_GET['sektor'] === 'all' ? 'selected' : '' ?>>Semua Sektor</option>
                <option value="Kuliner" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Kuliner' ? 'selected' : '' ?>>Kuliner</option>
                <option value="Fashion" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Fashion' ? 'selected' : '' ?>>Fashion</option>
                <option value="Pertanian" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Pertanian' ? 'selected' : '' ?>>Pertanian</option>
                <option value="Perdagangan" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Perdagangan' ? 'selected' : '' ?>>Perdagangan</option>
                <option value="Jasa" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Jasa' ? 'selected' : '' ?>>Jasa</option>
                <option value="Teknologi" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Teknologi' ? 'selected' : '' ?>>Teknologi</option>
                <option value="Lainnya" <?= isset($_GET['sektor']) && $_GET['sektor'] === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
            <button type="submit">Filter</button>
            <a href="?export=1<?= isset($_GET['sektor']) ? '&sektor=' . $_GET['sektor'] : '' ?>" class="btn-export">Cetak Laporan</a>
        </form>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID Pegawai</th>
            <th>Tanggal Input</th>
            <th>Nama Owner</th>
            <th>Alamat Owner</th>
            <th>Asal Owner</th>
            <th>No HP</th>
            <th>Nama Usaha</th>
            <th>Sektor Usaha</th>
            <th>Alamat Usaha</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_input']) ?></td>
                <td><?= htmlspecialchars($row['nama_owner']) ?></td>
                <td><?= htmlspecialchars($row['alamat_owner']) ?></td>
                <td><?= htmlspecialchars($row['asal_owner']) ?></td>
                <td><?= htmlspecialchars($row['handphone']) ?></td>
                <td><?= htmlspecialchars($row['nama_usaha']) ?></td>
                <td><?= htmlspecialchars($row['sektor_usaha']) ?></td>
                <td><?= htmlspecialchars($row['alamat_usaha']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
