<?php
session_start();
include "koneksi.php";

// Cek jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$nama_user_session = $_SESSION['nama'];

// Pesan feedback
$success_message = '';
$error_message = '';

// Proses update nama
if (isset($_POST['update_nama'])) {
    $new_nama = mysqli_real_escape_string($koneksi, $_POST['nama']);

    if (empty($new_nama)) {
        $error_message = "Nama tidak boleh kosong.";
    } else {
        $query_update_nama = "UPDATE users SET nama = '$new_nama' WHERE id = '$id_user'";
        if (mysqli_query($koneksi, $query_update_nama)) {
            $_SESSION['nama'] = $new_nama; // Update session
            $success_message = "Nama berhasil diperbarui.";
            $nama_user_session = $new_nama;
        } else {
            $error_message = "Gagal memperbarui nama: " . mysqli_error($koneksi);
        }
    }
}

// Proses update password
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ambil password lama dari database
    $query_get_pass = "SELECT password FROM users WHERE id = '$id_user'";
    $result_get_pass = mysqli_query($koneksi, $query_get_pass);
    $user_data = mysqli_fetch_assoc($result_get_pass);
    $hashed_password = $user_data['password'];

    // Verifikasi password lama
    if (!password_verify($current_password, $hashed_password)) {
        $error_message = "Password lama salah.";
    } elseif ($new_password != $confirm_password) {
        $error_message = "Konfirmasi password tidak cocok.";
    } else {
        // Hash password baru
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password di database
        $query_update_pass = "UPDATE users SET password = '$new_hashed_password' WHERE id = '$id_user'";
        if (mysqli_query($koneksi, $query_update_pass)) {
            $success_message = "Password berhasil diperbarui.";
        } else {
            $error_message = "Gagal memperbarui password: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* CSS umum */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f0f2f5; display: flex; }
        .container { display: flex; width: 100%; }
        
        /* Sidebar */
        .sidebar { width: 250px; background-color: #fff; padding: 20px 0; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .logo-section { padding: 0 20px 20px; border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .logo-section h3 { font-size: 18px; font-weight: 600; color: #4468a3; margin-bottom: 5px; }
        .logo-section p { font-size: 12px; color: #666; margin: 2px 0; }
        .nav { list-style: none; padding: 0; margin: 0; }
        .nav li { margin-bottom: 5px; }
        .nav a { display: flex; align-items: center; padding: 15px 20px; text-decoration: none; color: #333; transition: background-color 0.3s; font-size: 14px; }
        .nav a:hover, .nav a.active { background-color: #e6f0ff; color: #4468a3; font-weight: 600; }
        .nav a i { margin-right: 15px; width: 20px; }
        
        /* Main content */
        .main { flex: 1; display: flex; flex-direction: column; }
        .header { background-color: #fff; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: flex-end; align-items: center; }
        .profile { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 10px; width: 200px; background-color: #f8f9fa; border-radius: 8px; }
        .profile .name { font-size: 16px; font-weight: 600; color: #333; margin: 0; }
        .profile .role, .profile .address { font-size: 12px; color: #666; margin: 0; }
        .btn-logout { background-color: #4468a3; color: #fff; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 12px; text-decoration: none; margin-top: 10px; }
        .btn-logout:hover { background-color: #314d79; }
        
        .content { flex: 1; padding: 20px; }
        .content h2 { font-size: 24px; font-weight: 600; color: #333; margin-bottom: 20px; }
        
        /* Form Pengaturan */
        .settings-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .settings-box {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .settings-box h3 {
            font-size: 20px;
            color: #4468a3;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .settings-box label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .settings-box input[type="text"],
        .settings-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn-submit {
            background-color: #4468a3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-submit:hover {
            background-color: #314d79;
        }
        
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: 600;
            display: none; /* Default hidden */
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo-section">
                <h3>APLIKASI DATABASE USAHA</h3>
                <p>Bandung - Jawa Barat</p>
                <p>Bank Nagari Cabang Bandung</p>
                <p>Jl. Buah Batu No. 240, Cijagra</p>
            </div>
            <ul class="nav">
                <li><a href="login.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="input.php"><i class="fas fa-plus"></i> Input Data</a></li>
                <li><a href="data_usaha.php"><i class="fas fa-database"></i> Data Usaha</a></li>
                <li><a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                <li><a href="pengaturan.php" class="active"><i class="fas fa-cog"></i> Pengaturan</a></li>
            </ul>
        </aside>

        <main class="main">
            <header class="header">
                <div class="profile">
                    <p class="name"><?php echo htmlspecialchars($nama_user_session); ?></p>
                    <p class="role">Admin</p>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            </header>

            <div class="content">
                <h2>Pengaturan Akun</h2>

                <?php if (!empty($success_message)): ?>
                    <div class="message success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <div class="message error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="settings-container">
                    <div class="settings-box">
                        <h3>Ubah Nama</h3>
                        <form action="pengaturan.php" method="POST">
                            <label for="nama">Nama Lengkap:</label>
                            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama_user_session); ?>" required>
                            <button type="submit" name="update_nama" class="btn-submit">Simpan Nama Baru</button>
                        </form>
                    </div>

                    <div class="settings-box">
                        <h3>Ubah Password</h3>
                        <form action="pengaturan.php" method="POST">
                            <label for="current_password">Password Lama:</label>
                            <input type="password" id="current_password" name="current_password" required>
                            
                            <label for="new_password">Password Baru:</label>
                            <input type="password" id="new_password" name="new_password" required>
                            
                            <label for="confirm_password">Konfirmasi Password Baru:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            
                            <button type="submit" name="update_password" class="btn-submit">Simpan Password Baru</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</main>
    <footer style="background-color: #f8f9fa; padding: 20px 40px; text-align: center; border-top: 1px solid #e0e0e0;">
        <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <p style="margin: 0; color: #555;">&copy; 2023 Aplikasi Database Usaha. All rights reserved.</p>

            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                <img src="assets/tagline.png" alt="Tagline Bank Nagari" style="height: 30px;">
                <img src="assets/taglinekedua.png" alt="Tagline Kedua Bank Nagari" style="height: 30px;">
            </div>

            <p style="margin: 0; color: #888;">Developed by Ollin</p>
        </div>
    </footer>
</html>