<?php
// koneksi.php
$server = "localhost";
$username_db = "root";
$password_db = "";
$database = "db_banknagari";

// Buat koneksi
$koneksi = mysqli_connect($server, $username_db, $password_db, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>