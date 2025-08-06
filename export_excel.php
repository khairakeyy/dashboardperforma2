<?php
require 'vendor/autoload.php';
include 'koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Nama Usaha');
$sheet->setCellValue('C1', 'Nama Pemilik');
$sheet->setCellValue('D1', 'No HP');
$sheet->setCellValue('E1', 'Alamat');
$sheet->setCellValue('F1', 'Sektor');

// Ambil data dari database
$query = mysqli_query($conn, "SELECT * FROM data_usaha");
$i = 2;
$no = 1;

while ($data = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $i, $no++);
    $sheet->setCellValue('B' . $i, $data['nama_usaha']);
    $sheet->setCellValue('C' . $i, $data['nama_pemilik']);
    $sheet->setCellValue('D' . $i, $data['handphone']);
    $sheet->setCellValue('E' . $i, $data['alamat']);
    $sheet->setCellValue('F' . $i, $data['sektor']);
    $i++;
}

// Export ke Excel
$writer = new Xlsx($spreadsheet);
$filename = 'laporan_data_usaha.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
?>