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

    // $filename = 'laporan_usaha_diaspora.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header("Content-Disposition: attachment; filename=\"$filename\"");
    // header('Cache-Control: max-age=0');

    // $writer = new Xlsx($spreadsheet);
    // $writer->save('php://output');
    // exit;
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
    <title>Dashboard | Aplikasi Database Usaha</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f6f8;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #0e4b6c;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 40px auto;
            padding: 20px;
            border-radius: 15px;
            max-width: 1100px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-container img {
            height: 40px;
        }

        .logo-container h2 {
            margin: 0;
            font-size: 20px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        h1 {
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .nav {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .nav a {
            text-decoration: none;
            padding: 12px 25px;
            background: #0e4b6c;
            color: white;
            border-radius: 8px;
            font-weight: bold;
        }

        .nav a:hover {
            background: #093a52;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .stat-box {
            background: #1f2d3d;
            color: white;
            border-radius: 12px;
            padding: 20px 30px;
            flex: 1;
            min-width: 150px;
        }

        .stat-title {
            font-size: 14px;
            margin-bottom: 5px;
            color: #ccc;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #999;
        }

        img.dashboard-image {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            margin: 20px auto;
        }

        .slides {
            display: none;
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

        .fade {
            animation: fade 1s ease-in-out;
        }

        @keyframes fade {
            from {
                opacity: 0.4
            }

            to {
                opacity: 1
            }
        }

        .active-dot {
            opacity: 1 !important;
        }

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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
        .filter select {
            padding: 8px 12px;
            margin-right: 10px;
        }
        .filter button {
            padding: 8px 12px;
            background: #0e4b6c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
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
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <img src="assets/logohitam.png" alt="Logo Bank Nagari">
            <img src="assets/ollinwarna.png" alt="Logo Ollin" style="height: 40px;">
            <h2>Aplikasi Database Usaha Diaspora</h2>
        </div>
        <form method="post" action="login.php">
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>

    <!-- Konten utama -->
    <div class="container">
        <h1 class="text-3xl font-bold text-center mb-4">Selamat Datang</h1>
        <div id="controls-carousel" class="relative w-full" data-carousel="static">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="assets/1.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                    <img src="assets/2.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
                <!-- Item 3 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="assets/3.jpeg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                </div>
            </div>
            <!-- Slider controls -->
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                    </svg>
                    <span class="sr-only">Previous</span>
                </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                    </svg>
                    <span class="sr-only">Next</span>
                </span>
            </button>
        </div>
        <!-- Carousel indicators -->
        <div class="flex justify-center items-center space-x-3 mt-4">
            <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-label="Slide 2" data-carousel-slide-to="1"></button>
            <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-label="Slide 3" data-carousel-slide-to="2"></button>
        </div>

        <div class="container">
            <h2 class="text-3xl font-bold text-center mb-4">Laporan Data Usaha Diaspora</h2>

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
                    <!-- <a href="?export=1<?= isset($_GET['sektor']) ? '&sektor=' . $_GET['sektor'] : '' ?>" class="btn-export">Cetak Laporan</a> -->
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

        <footer class="bg-gray-100 py-4 px-6 mt-10 text-center text-sm text-gray-600">
            <div class="flex flex-col items-center justify-center space-y-2 md:space-y-0 md:flex-row md:justify-between md:items-center">
                <p class="text-sm">&copy; 2023 Aplikasi Database Usaha. All rights reserved.</p>

                <div class="flex items-center space-x-3">
                    <img src="assets/tagline.png" alt="Tagline 1" class="h-6 md:h-8">
                    <img src="assets/taglinekedua.png" alt="Tagline 2" class="h-6 md:h-8">
                </div>

                <p class="text-sm">Developed by <span class="font-medium">Ollin</span></p>
            </div>
        </footer>

    </div>

    <script>
        let slideIndex = 0;
        let slides = document.getElementsByClassName("slides");
        let dots = document.getElementsByClassName("dot");

        function showSlides() {
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                dots[i].classList.remove("active-dot");
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].classList.add("active-dot");
            setTimeout(showSlides, 4000); // 4 detik
        }

        document.addEventListener("DOMContentLoaded", function() {
            showSlides();
        });
    </script>


</body>

</html>