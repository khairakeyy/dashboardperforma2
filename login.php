<?php
session_start();
include "koneksi.php";

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi.";
    } else {
        $username = mysqli_real_escape_string($koneksi, $username);
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama_lengkap'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // jika dibutuhkan

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Usaha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h2 {
            color: #4468a3;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }

        button {
            padding: 12px;
            background-color: #4468a3;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #314d79;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #4468a3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            input, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Aplikasi Usaha</h2>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required autofocus autocomplete="off">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required autocomplete="off">

            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
