<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bouclean - Bank Sampah Plombokan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0dcaf0;
            --secondary: #198754;
            --blue-dark: #0d6efd;
            --dark: #1a1d20;
            --light: #f8f9fa;
            --glass: rgba(255, 255, 255, 0.8);
        }
        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            scroll-behavior: smooth;
            width: 100%;
            position: relative;
        }

        /* Prevent any element from causing overflow */
        * {
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        /* Navbar Effects */
        .navbar {
            background: transparent;
            transition: all 0.4s ease;
            padding: 20px 0;
        }
        .navbar.scrolled {
            background: var(--glass);
            backdrop-filter: blur(15px);
            padding: 12px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .nav-link {
            font-weight: 600;
            color: white !important;
            margin: 0 15px;
            position: relative;
            transition: all 0.3s;
        }
        .navbar.scrolled .nav-link {
            color: var(--dark) !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }
        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            padding-top: 80px; /* Add padding to prevent content from being hidden by fixed navbar */
            padding-bottom: 20px;
            box-sizing: border-box; /* Ensure padding is included in element's total width and height */
        }
        .hero-title {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -2px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #0dcaf0 0%, #198754 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Buttons */
        .btn-modern {
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary), var(--blue-dark));
            border: none;
            color: white;
        }
        .btn-primary-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(13, 202, 240, 0.3);
            color: white;
        }

        /* Cards */
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 25px;
            padding: 40px;
            transition: all 0.4s ease;
        }
        .glass-card:hover {
            transform: translateY(-15px);
            background: white;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        }

        .feature-icon-box {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.1), rgba(25, 135, 84, 0.1));
            color: var(--blue-dark);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 25px;
            transition: all 0.3s;
        }
        .glass-card:hover .feature-icon-box {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            transform: rotate(10deg);
        }

        /* Sections */
        .section-padding {
            padding: 120px 0;
        }
        .section-title {
            font-weight: 800;
            margin-bottom: 50px;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 4px;
            background: var(--primary);
            border-radius: 10px;
        }

        footer {
            background: var(--dark);
            color: white;
            padding: 80px 0 40px;
        }

        /* Floating Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .float-anim {
            animation: float 4s ease-in-out infinite;
        }

        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 3rem; /* Adjusted for better fit on tablets */
            }
            .section-padding {
                padding: 80px 0;
            }
            .section-title {
                font-size: 2.5rem;
            }
            .navbar-brand img {
                height: 35px; /* Slightly smaller logo on tablets */
            }
            .navbar-brand .fw-bold {
                font-size: 1.8rem !important; /* Adjust brand text size */
            }
        }

        @media (max-width: 767.98px) {
            .hero-title {
                font-size: 2.2rem;
                letter-spacing: -1px;
            }
            .lead {
                font-size: 1rem !important;
                padding: 0 10px;
            }
            .btn-modern {
                padding: 12px 25px;
                font-size: 0.85rem;
                width: 100%;
            }
            .section-padding {
                padding: 60px 0;
            }
            .section-title {
                font-size: 1.8rem;
            }
            .navbar-brand img {
                height: 32px;
            }
            .navbar-brand .fw-bold {
                font-size: 1.4rem !important;
            }
            .navbar-toggler {
                padding: 0.4rem 0.6rem;
                font-size: 1rem;
                order: 2; /* Brand left, toggler right */
            }
            .navbar-brand {
                order: 1;
            }
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(15px);
                margin-top: 15px;
                border-radius: 15px;
                padding: 20px;
                order: 3;
                box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            }
            .navbar-collapse .nav-link {
                color: var(--dark) !important;
                padding: 10px 0;
                border-bottom: 1px solid rgba(0,0,0,0.05);
            }
            .navbar-collapse .nav-link:last-child {
                border-bottom: none;
            }
            .navbar-collapse .btn-modern {
                margin-top: 10px;
            }
            .hero-section {
                padding-top: 100px;
                text-align: center;
            }
            .d-flex.flex-wrap.gap-3 {
                flex-direction: column;
                gap: 15px !important;
            }
        }

        @media (max-width: 575.98px) {
            .hero-title {
                font-size: 1.8rem;
            }
            .lead {
                font-size: 0.9rem !important;
            }
            .btn-modern {
                width: 100%;
            }
            .navbar-brand .fw-bold {
                font-size: 1.2rem !important;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar-main">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar-main">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('img/Bougenville.png') }}" alt="Logo" height="45" class="me-2">
                <span class="fw-bold fs-3 text-white brand-text-mobile" id="brand-text">Bouclean</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white toggler-icon"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    <li class="nav-item ms-lg-4 w-100-mobile">
                        <a href="{{ route('login') }}" class="btn btn-primary-modern btn-modern w-100">Login Sistem</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right" data-aos-duration="1000">
                    <h6 class="text-primary fw-bold text-uppercase ls-2 mb-3">Selamat Datang di Bouclean</h6>
                    <h1 class="hero-title mb-4">Kampung Warga <br><span class="text-gradient">Plombokan</span></h1>
                    <p class="lead fs-4 mb-5 opacity-75">Wujudkan Semarang Utara yang lebih asri. <br>"Dulu sampah sekarang rupiah yang berkah."</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary-modern btn-modern py-3 px-5 fs-5">Mulai Sekarang</a>
                        <a href="#about" class="btn btn-outline-light btn-modern py-3 px-5 fs-5">Pelajari Alur</a>
                    </div>
                </div>
               
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="About" class="img-fluid rounded-5 shadow-2xl">
                      
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                    <h6 class="text-success fw-bold text-uppercase mb-3 mt-4 mt-lg-0">Visi & Misi</h6>
                    <h2 class="fw-bold display-5 mb-4">Mengelola Sampah Menjadi Berkah</h2>
                    <p class="text-muted fs-5 mb-4">Bouclean hadir sebagai solusi digital untuk warga Plombokan dalam mengelola limbah rumah tangga secara profesional, transparan, dan bernilai ekonomis.</p>
                    <div class="d-flex mb-4">
                        <div class="feature-icon-box me-3"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h5 class="fw-bold">Terpercaya & Transparan</h5>
                            <p class="text-muted">Setiap transaksi iuran dan sampah tercatat otomatis dalam sistem.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="feature-icon-box me-3"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <h5 class="fw-bold">Pemberdayaan Ekonomi</h5>
                            <p class="text-muted">Sampah anorganik Anda dikonversi menjadi saldo tabungan warga.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section-padding bg-light" id="features">
        <div class="container text-center">
            <h6 class="text-primary fw-bold text-uppercase mb-3">Layanan Kami</h6>
            <h2 class="section-title">Fitur Utama Bouclean</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="glass-card h-100">
                        <div class="feature-icon-box mx-auto"><i class="bi bi-recycle"></i></div>
                        <h4 class="fw-bold mb-3">Pilah Sampah</h4>
                        <p class="text-muted">Setor sampah anorganik (plastik, kertas, logam) dan pantau berat serta harganya langsung dari dashboard.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-card h-100">
                        <div class="feature-icon-box mx-auto"><i class="bi bi-wallet2"></i></div>
                        <h4 class="fw-bold mb-3">Iuran Rutin</h4>
                        <p class="text-muted">Pembayaran iuran sampah bulanan kini lebih mudah dan tercatat rapi tanpa perlu kwitansi kertas.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="glass-card h-100">
                        <div class="feature-icon-box mx-auto"><i class="bi bi-geo-alt"></i></div>
                        <h4 class="fw-bold mb-3">Data Wilayah</h4>
                        <p class="text-muted">Administrasi warga Plombokan yang terintegrasi untuk memudahkan pendataan perpindahan dan status.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section-padding" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8" data-aos="fade-right">
                    <h2 class="fw-bold display-6 mb-4">Hubungi Kami</h2>
                    <p class="text-muted fs-5 mb-5">Punya kendala atau ingin mendaftar sebagai warga baru? Tim admin Plombokan siap membantu Anda.</p>
                    <div class="card border-0 bg-light p-4 rounded-4 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="feature-icon-box mb-0 me-3"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0">Lokasi Kantor</h6>
                                <p class="mb-0 small text-muted">Balai Warga Plombokan, Semarang Utara</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 bg-light p-4 rounded-4">
                        <div class="d-flex align-items-center">
                            <div class="feature-icon-box mb-0 me-3"><i class="bi bi-whatsapp"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0">WhatsApp</h6>
                                <p class="mb-0 small text-muted">+62 812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 mt-5 mt-lg-0" data-aos="fade-left">
                    <div class="glass-card">
                        <form>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-lg border-0 bg-light" placeholder="Masukkan nama Anda">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pesan Anda</label>
                                <textarea class="form-control form-control-lg border-0 bg-light" rows="4" placeholder="Tuliskan pesan atau pertanyaan"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary-modern btn-modern w-100 py-3">Kirim Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <img src="{{ asset('img/Bougenville.png') }}" alt="Logo" height="60" class="mb-4">
            <h3 class="fw-bold mb-2">Bouclean</h3>
            <p class="opacity-50 mb-5">Bank Sampah Digital Kampung Warga Plombokan</p>
            <div class="d-flex justify-content-center gap-4 mb-5">
                <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
            </div>
            <p class="small opacity-50 mb-0">&copy; {{ date('Y') }} Bouclean Plombokan. Made with <i class="bi bi-heart-fill text-danger"></i> for Semarang Utara.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            duration: 800
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const brandText = document.getElementById('brand-text');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                brandText.classList.replace('text-white', 'text-dark');
            } else {
                navbar.classList.remove('scrolled');
                brandText.classList.replace('text-dark', 'text-white');
            }
        });
    </script>
</body>
</html>