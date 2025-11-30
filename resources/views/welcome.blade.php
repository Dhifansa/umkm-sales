<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Sales Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            top: -200px;
            right: -200px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            bottom: -150px;
            left: -150px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            animation: fadeInUp 1.2s ease;
        }

        .hero-buttons {
            animation: fadeInUp 1.4s ease;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            margin: 0 10px;
            transition: all 0.3s;
        }

        .btn-hero-primary {
            background: white;
            color: #667eea;
            border: none;
        }

        .btn-hero-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .btn-hero-outline {
            border: 2px solid white;
            color: white;
            background: transparent;
        }

        .btn-hero-outline:hover {
            background: white;
            color: #667eea;
            transform: translateY(-5px);
        }

        .hero-image {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(102,126,234,0.3);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
        }

        .feature-card h4 {
            margin-bottom: 15px;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-item h2 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-item p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta {
            padding: 100px 0;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .cta p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 40px;
        }

        /* Footer */
        .footer {
            background: #2d3748;
            color: white;
            padding: 40px 0 20px;
        }

        .footer h5 {
            margin-bottom: 20px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Kelola UMKM Anda Dengan Lebih Mudah</h1>
                    <p>Sistem manajemen penjualan yang powerful, mudah digunakan, dan gratis untuk UMKM Indonesia</p>
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Mulai Sekarang
                            </a>
                            <a href="#features" class="btn btn-hero btn-hero-outline">
                                <i class="bi bi-info-circle"></i> Pelajari Lebih Lanjut
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="bi bi-shop" style="font-size: 20rem; color: rgba(255,255,255,0.2);"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3">Fitur Unggulan</h2>
                <p class="lead text-muted">Semua yang Anda butuhkan untuk mengelola bisnis UMKM</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <h4>Point of Sale (POS)</h4>
                        <p>Interface kasir yang cepat dan mudah digunakan untuk transaksi harian</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h4>Manajemen Produk</h4>
                        <p>Kelola produk, stok, harga, dan kategori dengan mudah dalam satu tempat</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Laporan Penjualan</h4>
                        <p>Analisis penjualan dengan grafik dan laporan lengkap real-time</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Manajemen Pelanggan</h4>
                        <p>Simpan data pelanggan dan lacak riwayat pembelian mereka</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h4>Riwayat Transaksi</h4>
                        <p>Catat semua transaksi dengan detail lengkap dan mudah dicari</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-printer"></i>
                        </div>
                        <h4>Cetak Struk</h4>
                        <p>Cetak struk pembelian professional untuk pelanggan Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 stat-item">
                    <h2><i class="bi bi-shop"></i> 1000+</h2>
                    <p>UMKM Terdaftar</p>
                </div>
                <div class="col-md-3 stat-item">
                    <h2><i class="bi bi-receipt"></i> 50K+</h2>
                    <p>Transaksi Berhasil</p>
                </div>
                <div class="col-md-3 stat-item">
                    <h2><i class="bi bi-star-fill"></i> 4.8/5</h2>
                    <p>Rating Pengguna</p>
                </div>
                <div class="col-md-3 stat-item">
                    <h2><i class="bi bi-clock"></i> 24/7</h2>
                    <p>Dukungan Online</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Siap Meningkatkan Bisnis Anda?</h2>
            <p>Mulai kelola UMKM Anda dengan lebih efisien hari ini</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-hero btn-hero-primary">
                    <i class="bi bi-speedometer2"></i> Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-hero btn-hero-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login Sekarang
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="bi bi-shop-window"></i> UMKM Sales</h5>
                    <p class="text-white-50">Sistem manajemen penjualan terpercaya untuk UMKM Indonesia</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="#">Dokumentasi</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul>
                        <li><i class="bi bi-envelope"></i> support@umkmsales.com</li>
                        <li><i class="bi bi-telephone"></i> +62 812-3456-7890</li>
                        <li><i class="bi bi-geo-alt"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-white-50">
                <p>&copy; 2024 UMKM Sales Management. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
