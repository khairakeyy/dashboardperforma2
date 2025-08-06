<?php
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$db = "db_banknagari";
$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query data statistik
$totalQuery = "SELECT COUNT(*) as total FROM usaha_diaspora";
$kulinerQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha = 'Kuliner'";
$fashionQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha = 'Fashion'";
$pertanianQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha = 'Pertanian'";
$jasaQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha = 'Jasa'";
$teknologiQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha = 'Teknologi'";
$perdaganganQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha = 'Perdagangan'";
$lainnyaQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha NOT IN ('Kuliner', 'Fashion', 'Pertanian', 'Jasa', 'Teknologi', 'Perdagangan')";
$lainnyaQuery = "SELECT COUNT(*) as total FROM usaha_diaspora WHERE sektor_usaha NOT IN ('Kuliner', 'Fashion')";

$total = $conn->query($totalQuery)->fetch_assoc()['total'];
$kuliner = $conn->query($kulinerQuery)->fetch_assoc()['total'];
$fashion = $conn->query($fashionQuery)->fetch_assoc()['total'];
$pertanian = $conn->query($pertanianQuery)->fetch_assoc()['total'];
$jasa = $conn->query($jasaQuery)->fetch_assoc()['total'];
$teknologi = $conn->query($teknologiQuery)->fetch_assoc()['total'];
$perdagangan = $conn->query($perdaganganQuery)->fetch_assoc()['total'];
$lainnya = $conn->query($lainnyaQuery)->fetch_assoc()['total'];
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
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
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
        <form method="post" action="logout.php">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- Konten utama -->
    <div class="container">
        <h1 class="text-3xl font-bold text-center mb-4"> <?php echo "Selamat Datang, " . ($_SESSION['nama'] ?? ''); ?>!</h1>
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


        <div class="nav">
            <a href="input.php">Input Data</a>
            <a href="laporan.php">Laporan</a>
        </div>

        <!-- <h2 class="text-3xl font-bold text-center mb-4">Data Terbaru</h2>
        <div class="cards">
            <div class="card">
                <h3>Total Data</h3>
                <p><?= $counts['Total'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Kuliner</h3>
                <p><?= $counts['Kuliner'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Fashion</h3>
                <p><?= $counts['Fashion'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Pertanian</h3>
                <p><?= $counts['Pertanian'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Jasa</h3>
                <p><?= $counts['Jasa'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Teknologi</h3>
                <p><?= $counts['Teknologi'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Perdagangan</h3>
                <p><?= $counts['Perdagangan'] ?? 0 ?></p>
            </div>
            <div class="card">
                <h3>Sektor Lainnya</h3>
                <p><?= $counts['Lainnya'] ?? 0 ?></p>
            </div>
        </div> -->

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