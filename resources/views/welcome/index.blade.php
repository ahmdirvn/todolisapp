<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todolisap - Your Smart Task Manager</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f3f9;
            color: #343a40;
        }

        /* Navbar */
        .navbar {
            background-color: #6f42c1;
        }
        .navbar .logo {
            font-weight: 700;
            font-size: 22px;
            color: white;
            text-decoration: none;
        }
        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
        }

        /* Hero */
        .hero {
            padding: 80px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .hero-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            text-align: center;
        }
        .hero-card h1 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #6f42c1;
        }

        /* Task Preview */
        .task-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        .task-card span {
            font-weight: 500;
        }
        .badge-custom {
            background-color: #6f42c1;
        }

        /* About */
        #about {
            padding: 60px 20px;
        }
        #about h2 {
            color: #6f42c1;
            margin-bottom: 20px;
        }

        /* CTA Banner */
        .cta-banner {
            background: linear-gradient(135deg, #6f42c1, #9d6fe7);
            color: white;
            padding: 50px 20px;
            text-align: center;
        }
        .cta-banner h2 {
            margin-bottom: 20px;
        }

        footer {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="logo" href="#">Todolisap</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-white" style="padding:0;">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-card">
        <h1>Kelola Tugas Harianmu dengan Mudah</h1>
        <p>Todolisap adalah aplikasi untuk mencatat, mengatur, dan menyelesaikan tugas sehari-hari secara efisien.</p>
        <a href="{{ route('register') }}" class="btn btn-lg btn-light mt-3" style="background:#6f42c1; color:white;">Mulai Sekarang</a>
    </div>
</section>

<!-- Task Preview -->
<section id="preview" class="container mb-5">
    <h2 class="text-center mb-4" style="color:#6f42c1;">Contoh Daftar Tugas</h2>
    <div class="task-card">
        <span>Mengerjakan laporan kuliah</span>
        <span class="badge badge-custom">Pending</span>
    </div>
    <div class="task-card">
        <span>Meeting dengan tim project</span>
        <span class="badge bg-success">Selesai</span>
    </div>
    <div class="task-card">
        <span>Beli perlengkapan kerja</span>
        <span class="badge badge-custom">Pending</span>
    </div>
</section>

<!-- About -->
<section id="about" class="text-center">
    <h2>Tentang Todolisap</h2>
    <p>Todolisap dirancang untuk membantu kamu tetap fokus dan teratur. Dengan fitur CRUD sederhana, semua aktivitasmu bisa dikelola dalam satu tempat yang rapi dan praktis.</p>
</section>

<!-- CTA Banner -->
<section class="cta-banner">
    <h2>Mulai Gunakan Todolisap Sekarang</h2>
    <p>Daftar akun gratis dan atur daftar tugasmu dengan lebih produktif.</p>
    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Daftar & Mulai</a>
</section>

<!-- Footer -->
<footer>
    &copy; 2025 Todolisap. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
