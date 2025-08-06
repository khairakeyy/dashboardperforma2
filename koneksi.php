<?php
// koneksi.php
$server = "sql311.infinityfree.com";
$username_db = "if0_39646060";
$password_db = "dashboard02";
$database = "if0_39646060_db_banknagari";

// Buat koneksi
$koneksi = mysqli_connect($server, $username_db, $password_db, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>