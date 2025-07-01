<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang</title>
    <script>
        // Segera cek local storage dan tambahkan kelas 'no-transition' dan 'toggled'
        // ke <body> atau #wrapper jika sidebar harusnya collapsed.
        // Ini dilakukan sebelum CSS atau sisa JS dimuat untuk menghindari flicker.
        const isSidebarToggledOnLoad = localStorage.getItem('sidebarToggled') === 'true';
        if (isSidebarToggledOnLoad) {
            // Kita akan menargetkan body atau wrapper. Mari target body.
            // Ini akan memerlukan penyesuaian CSS agar body.toggled bekerja.
            // Alternatif yang lebih aman dan sering digunakan adalah
            // menambahkan kelas ke #wrapper.
            document.documentElement.classList.add('preload'); // Untuk menghindari transisi awal
            document.documentElement.classList.add('sidebar-toggled-on-load'); // Kelas penanda
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9FvFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="d-flex" id="wrapper">

        <?php include 'includes/sidebar.php'; ?>

        <div id="page-content-wrapper" class="flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-text ms-auto">
                        Selamat Datang, **<?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Pengguna'); ?>**
                    </span>
                </div>
            </nav>

            <div class="container-fluid py-4">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                $allowed_pages = ['dashboard', 'tanah', 'asset', 'bangunan', 'jij', 'aset_lain', 'ruang', 'sarana', 'bhp', 'peminjaman', 'profil', 'pengguna'];

                if (in_array($page, $allowed_pages)) {
                    $file_to_include = 'pages/' . $page . '.php';
                    if (file_exists($file_to_include)) {
                        include $file_to_include;
                    } else {
                        include 'pages/404.php';
                    }
                } else {
                    include 'pages/dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const wrapper = document.getElementById('wrapper');
            const sidebar = document.getElementById('sidebar');

            // Hapus kelas 'preload' setelah DOMContentLoaded untuk mengaktifkan transisi lagi
            document.documentElement.classList.remove('preload');

            // --- Logika Local Storage untuk Status Sidebar ---
            // Saat halaman dimuat, status sudah diterapkan oleh script di <head>
            // Logika ini hanya perlu di event listener toggle
            sidebarToggle.addEventListener('click', function() {
                wrapper.classList.toggle('toggled');
                sidebar.classList.toggle('toggled');

                // Simpan status sidebar ke local storage
                if (wrapper.classList.contains('toggled')) {
                    localStorage.setItem('sidebarToggled', 'true');
                } else {
                    localStorage.setItem('sidebarToggled', 'false');
                }
            });

            // --- Logika Penanda Halaman Aktif (sama seperti sebelumnya) ---
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const currentPage = new URLSearchParams(window.location.search).get('page');

            navLinks.forEach(link => {
                link.classList.remove('active');

                const linkHref = link.getAttribute('href');
                if (linkHref) {
                    const linkUrl = new URL(linkHref, window.location.origin);
                    const linkPage = linkUrl.searchParams.get('page');

                    if (linkHref === 'logout.php') {
                        return;
                    }

                    if (!currentPage && linkPage === 'dashboard') {
                        link.classList.add('active');
                    } else if (currentPage && currentPage === linkPage) {
                        link.classList.add('active');
                    }
                }
            });

            if (!currentPage) {
                const dashboardLink = document.querySelector('.sidebar .nav-link[href="index.php?page=dashboard"]');
                if (dashboardLink) {
                    dashboardLink.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>