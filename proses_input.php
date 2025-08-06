<?php
session_start();
include "koneksi.php";

// Cek jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan sanitasi
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $nama_pegawai = mysqli_real_escape_string($koneksi, $_POST['nama_pegawai']);
    $tanggal_input = mysqli_real_escape_string($koneksi, $_POST['tanggal_input']);
    $nama_owner = mysqli_real_escape_string($koneksi, $_POST['nama_owner']);
    $alamat_owner = mysqli_real_escape_string($koneksi, $_POST['alamat_owner']);
    $asal_owner = mysqli_real_escape_string($koneksi, $_POST['asal_owner']);
    $handphone = mysqli_real_escape_string($koneksi, $_POST['handphone']);
    $foto_owner = mysqli_real_escape_string($koneksi, $_POST['foto_owner']);
    $nama_usaha = mysqli_real_escape_string($koneksi, $_POST['nama_usaha']);
    $alamat_usaha = mysqli_real_escape_string($koneksi, $_POST['alamat_usaha']);
    $sektor_usaha = mysqli_real_escape_string($koneksi, $_POST['sektor_usaha']);

    // Query untuk menyimpan data ke tabel 'usaha_diaspora'
    $query = "INSERT INTO usaha_diaspora (id, nama_pegawai, tanggal_input, nama_owner, alamat_owner, asal_owner, handphone, foto_owner, nama_usaha, alamat_usaha, sektor_usaha) VALUES (
        '$id',
        '$nama_pegawai',
        '$tanggal_input',
        '$nama_owner',
        '$alamat_owner',
        '$asal_owner',
        '$handphone',
        '$foto_owner',
        '$nama_usaha',
        '$alamat_usaha',
        '$sektor_usaha'
    )";

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect ke halaman dashboard atau halaman data usaha
        header("Location: dashboard.php?status=success");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        die("Gagal menyimpan data: " . mysqli_error($koneksi));
    }
} else {
    // Jika diakses tanpa submit form, redirect ke halaman input
    header("Location: input.php");
    exit();
}
?>