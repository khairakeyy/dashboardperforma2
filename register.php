<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user'; // default role (jika tidak disediakan dalam form)
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);

    $sql = "INSERT INTO users (username, password, role, nama_lengkap)
            VALUES ('$username', '$password', '$role', '$nama_lengkap')";

    if (mysqli_query($koneksi, $sql)) {
        $success = "Registrasi berhasil! Silakan login.";
    } else {
        $error = "Gagal register: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .register-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #2b4f81;
            margin-bottom: 25px;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        form input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #2b4f81;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background-color: #1d375e;
        }

        .register-container p {
            text-align: center;
            margin-top: 15px;
        }

        .register-container a {
            color: #2b4f81;
            text-decoration: none;
        }

        .register-container a:hover {
            text-decoration: underline;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
        }

        footer img {
            height: 24px;
            margin: 0 5px;
        }

        @media (max-width: 500px) {
            .register-container {
                padding: 20px;
            }

            footer img {
                height: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrasi Akun</h2>

        <?php if (!empty($error)) : ?>
            <div class="error-message"><?= $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="success-message"><?= $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

    <footer>
        <div class="tagline">
            <img src="assets/ollinwarna.png" alt="Logo Ollin">
            <img src="assets/tagline.png" alt="All in your hand">
            <img src="assets/taglinekedua.png" alt="Ollin Logo 2">
        </div>
    </footer>
</body>
</html>
