<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-MGMP Informatika SMP Kab. Ciamis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: "Inter", sans-serif; background-color: #f8fafc; color: #1e293b; }
        .navbar-custom { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e3a8a 100%); padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.85) !important; font-size: 0.9rem; font-weight: 500; padding: 6px 14px !important; border-radius: 20px; transition: all 0.2s ease; }
        .nav-link:hover { color: #ffffff !important; background-color: rgba(255,255,255,0.15); }
        
        .hero-section { 
            background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.25), transparent 40%), linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #1e3a8a 80%, #2563eb 100%); 
            color: white; 
            padding: 90px 0 80px; 
            position: relative; 
            overflow: hidden; 
        }
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.4;
            pointer-events: none;
        }

        .card-custom { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .card-custom:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); }
        
        .hero-glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 18px 24px;
            color: white;
            transition: all 0.3s ease;
        }
        .hero-glass-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }

        .section-title { position: relative; padding-bottom: 12px; margin-bottom: 35px; font-weight: 800; letter-spacing: -0.5px; }
        .section-title::after { content: ""; position: absolute; left: 0; bottom: 0; width: 50px; height: 4px; background: linear-gradient(90deg, #2563eb, #3b82f6); border-radius: 2px; }
        .extra-small { font-size: 0.75rem; }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2 me-4" href="{{ route('home') }}">
                <div class="rounded-circle bg-white text-primary p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-laptop-code fs-6"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-6 lh-1 fw-bold tracking-wide text-white">SIM-MGMP</span>
                    <span class="extra-small text-white-50 font-monospace mt-1" style="font-size: 0.65rem; letter-spacing: 1px;">INFORMATIKA CIAMIS</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navWelcome">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navWelcome">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item"><a class="nav-link px-3 text-white-80" href="#profil"><i class="fa-solid fa-building-user me-1 text-info"></i> Profil MGMP</a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-white-80" href="#kegiatan"><i class="fa-solid fa-calendar-check me-1 text-warning"></i> Kegiatan</a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-white-80" href="{{ route('documentations.index') }}"><i class="fa-solid fa-camera-retro me-1 text-warning"></i> Dokumentasi</a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-white-80" href="{{ route('questions.index') }}"><i class="fa-solid fa-file-pdf me-1 text-danger"></i> Bank Soal (.PDF)</a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-white-80" href="{{ route('materials.index') }}"><i class="fa-solid fa-book-open-reader me-1 text-success"></i> Bahan Ajar Digital</a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-white-80" href="{{ route('generator.index') }}"><i class="fa-solid fa-wand-magic-sparkles me-1 text-warning"></i> Generator AI</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold text-primary">
                            <i class="fa-solid fa-gauge me-1"></i> Dashboard SIM
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold border border-white border-opacity-20">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login Anggota
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="hero-section text-center position-relative">
        <div class="container position-relative" style="z-index: 2;">
            <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 text-white rounded-pill px-4 py-2 fw-semibold mb-4 border border-white border-opacity-20 shadow-sm" style="backdrop-filter: blur(8px);">
                <i class="fa-solid fa-award text-warning fs-5"></i>
                <span class="small">Portal Resmi MGMP Informatika SMP Kabupaten Ciamis</span>
            </div>

            <h1 class="fw-extrabold display-4 mb-3 text-white tracking-tight" style="font-weight: 800;">
                Transformasi Digital & Pusat Sumber Belajar Informatika
            </h1>

            <p class="lead text-white-80 max-w-2xl mx-auto mb-0 fs-5" style="max-width: 800px; opacity: 0.9;">
                Wadah kolaborasi profesional guru Informatika SMP se-Kabupaten Ciamis untuk mengelola keanggotaan, presensi digital QR Code, bahan ajar interaktif, modul Gemini AI, dan bank soal terpadu.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container py-5">
        <!-- PROFIL MGMP -->
        <section id="profil" class="mb-5 pt-3">
            <h4 class="section-title text-dark"><i class="fa-solid fa-building-user text-primary me-2"></i> Profil MGMP Informatika SMP Kab. Ciamis</h4>
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="card card-custom p-4 bg-white">
                        <h5 class="fw-bold text-primary mb-3">Musyawarah Guru Mata Pelajaran (MGMP) Informatika</h5>
                        <p class="text-muted mb-3">MGMP Informatika SMP Kabupaten Ciamis merupakan wadah asosiasi profesional dan ruang kolaborasi bagi seluruh bapak/ibu guru pengampu mata pelajaran Informatika SMP Negeri dan Swasta se-Kabupaten Ciamis.</p>
                        <p class="text-muted mb-0">Sistem Informasi SIM-MGMP dirancang untuk mendukung transparansi administrasi organisasi, memfasilitasi presensi digital QR Code pada kegiatan, serta menyediakan pusat berbagi perangkat ajar dan bank soal Informatika terstandar.</p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card card-custom p-4 bg-white">
                        <h5 class="fw-bold text-success mb-3"><i class="fa-solid fa-sitemap me-2"></i> Struktur Pengurus Organisasi</h5>
                        @foreach ($pengurus as $pos)
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                @if($pos->member && $pos->member->foto)
                                    <img src="{{ asset('storage/' . $pos->member->foto) }}" alt="{{ $pos->member->nama_lengkap }}" class="rounded-circle object-fit-cover border shadow-sm" style="width: 48px; height: 48px;">
                                @elseif($pos->member)
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                        {{ strtoupper(substr($pos->member->nama_lengkap, 0, 1)) }}
                                    </div>
                                @else
                                    <div class="bg-light p-2 rounded-circle text-primary fs-5 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="fa-solid fa-user-tie"></i></div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark small">{{ $pos->nama_jabatan }}</div>
                                    <div class="text-muted small">{{ $pos->member ? $pos->member->nama_dengan_gelar : "- (Belum diisi)" }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- KEGIATAN -->
        <section id="kegiatan" class="mb-5 pt-3">
            <h4 class="section-title text-dark"><i class="fa-solid fa-calendar-check text-primary me-2"></i> Agenda Kegiatan Organisasi</h4>
            <div class="row g-4">
                <div class="col-lg-12">
                    @foreach ($kegiatanMendatang as $act)
                        <div class="card card-custom p-4 bg-white mb-3 shadow-sm border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 mb-2 fw-semibold">{{ $act->tanggal_kegiatan }}</span>
                                    <h5 class="fw-bold text-dark mb-1">{{ $act->nama_kegiatan }}</h5>
                                    <p class="text-muted small mb-0"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $act->lokasi }} | <i class="fa-solid fa-clock me-1 text-warning"></i> {{ $act->waktu_mulai }} - {{ $act->waktu_selesai }} WIB</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- PEMBELAJARAN -->
        <section id="pembelajaran" class="mb-5 pt-3">
            <h4 class="section-title text-dark"><i class="fa-solid fa-book-bookmark text-primary me-2"></i> Pusat Pembelajaran & Bank Soal</h4>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card card-custom p-4 bg-white h-100">
                        <div class="p-3 bg-info-subtle text-info rounded-circle d-inline-block mb-3 fs-3" style="width: 60px; height: 60px;"><i class="fa-solid fa-folder-open"></i></div>
                        <h5 class="fw-bold text-dark mb-2">Bahan Ajar Digital Informatika</h5>
                        <p class="text-muted small mb-4">Akses Modul Ajar, LKPD, E-Book, dan tautan media pembelajaran interaktif Informatika untuk Kelas 7, 8, dan 9 Kurikulum Merdeka.</p>
                        <a href="{{ route("materials.index") }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto me-auto px-4">Akses Bahan Ajar <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-custom p-4 bg-white h-100">
                        <div class="p-3 bg-success-subtle text-success rounded-circle d-inline-block mb-3 fs-3" style="width: 60px; height: 60px;"><i class="fa-solid fa-list-check"></i></div>
                        <h5 class="fw-bold text-dark mb-2">Bank Soal Informatika</h5>
                        <p class="text-muted small mb-4">Kumpulan soal asesmen formatif, sumatif, dan PAS Informatika terstandar lengkap dengan indikator, level kognitif, dan pembahasan.</p>
                        <a href="{{ route("questions.index") }}" class="btn btn-outline-success btn-sm rounded-pill mt-auto me-auto px-4">Akses Bank Soal <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- INFORMASI -->
        <section id="informasi" class="mb-4 pt-3">
            <h4 class="section-title text-dark"><i class="fa-solid fa-bullhorn text-warning me-2"></i> Informasi & Pengumuman</h4>
            <div class="row g-3">
                @foreach ($pengumuman as $p)
                    <div class="col-md-6">
                        <div class="card card-custom p-4 bg-white h-100">
                            <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1 d-inline-block me-auto mb-2">{{ $p->created_at->format("d M Y") }}</span>
                            <h5 class="fw-bold text-dark mb-2">{{ $p->judul }}</h5>
                            <p class="text-muted small mb-0">{{ Str::limit($p->isi_pengumuman, 140) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <!-- FOOTER -->
    <footer class="bg-dark text-white-50 py-4 mt-5 border-top border-secondary">
        <div class="container text-center">
            <small>&copy; {{ date("Y") }} SIM-MGMP Informatika SMP Kabupaten Ciamis — All Rights Reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>