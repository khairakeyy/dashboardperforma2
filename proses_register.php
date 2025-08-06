<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);

    $sql = "INSERT INTO users (username, password, role, nama_lengkap)
            VALUES ('$username', '$password', '$role', '$nama_lengkap')";
    
    if (mysqli_query($koneksi, $sql)) {
        header("Location: login.php");
    } else {
        echo "Gagal register: " . mysqli_error($koneksi);
    }
}
?>
