<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-MGMP Informatika SMP Kab. Ciamis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; background-color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar-custom { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a8a 100%); padding: 10px 0; }
        .nav-link { color: rgba(255,255,255,0.85) !important; font-size: 0.875rem; font-weight: 500; margin: 0 2px; border-radius: 8px; padding: 7px 12px !important; transition: all 0.2s ease; }
        .nav-link:hover, .nav-link.active { color: #ffffff !important; background-color: rgba(255,255,255,0.15); }
        .dropdown-menu { animation: fadeIn 0.15s ease-in-out; }
        .dropdown-item { font-size: 0.85rem; font-weight: 500; }
        .dropdown-item:hover { background-color: #f1f5f9; }
        .dropdown-item.active { background-color: #2563eb !important; color: white !important; }
        .dropdown-item.active i { color: white !important; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .extra-small { font-size: 0.75rem; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-xl navbar-custom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-white fw-bold me-4" href="{{ auth()->check() ? route("dashboard") : route("home") }}">
                <div class="brand-icon-wrapper me-2 rounded-circle bg-white text-primary p-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-laptop-code fs-6"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-6 lh-1 fw-bold tracking-wide text-white">SIM-MGMP</span>
                    <span class="extra-small text-white-50 font-monospace mt-1" style="font-size: 0.65rem; letter-spacing: 1px;">INFORMATIKA CIAMIS</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("dashboard") ? "active" : "" }}" href="{{ route("dashboard") }}"><i class="fa-solid fa-chart-pie me-1 text-info"></i> Dashboard</a></li>

                        <!-- DATA ORGANISASI DROPDOWN -->
                        @if(in_array(auth()->user()->role ?? '', ['superadmin', 'admin']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs(['members.*', 'schools.*', 'positions.*']) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-sitemap me-1 text-warning"></i> Organisasi
                                </a>
                                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2">
                                    <li><a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs("members.*") ? "active" : "" }}" href="{{ route("members.index") }}"><i class="fa-solid fa-users text-primary me-2"></i> Anggota Guru</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs("positions.*") ? "active" : "" }}" href="{{ route("positions.index") }}"><i class="fa-solid fa-user-tie text-success me-2"></i> Struktur Pengurus</a></li>
                                    <li><a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs("schools.*") ? "active" : "" }}" href="{{ route("schools.index") }}"><i class="fa-solid fa-school text-info me-2"></i> Data Sekolah</a></li>
                                </ul>
                            </li>
                        @endif

                        <!-- AKADEMIK & PEMBELAJARAN DROPDOWN -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs(['questions.*', 'materials.*', 'generator.*']) ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-book-bookmark me-1 text-success"></i> Pembelajaran
                            </a>
                            <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 mt-2">
                                <li><a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs("questions.*") ? "active" : "" }}" href="{{ route("questions.index") }}"><i class="fa-solid fa-file-pdf text-danger me-2"></i> Bank Soal (.PDF)</a></li>
                                <li><a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs("materials.*") ? "active" : "" }}" href="{{ route("materials.index") }}"><i class="fa-solid fa-book-open-reader text-primary me-2"></i> Bahan Ajar Digital</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs("generator.*") ? "active" : "" }}" href="{{ route("generator.index") }}"><i class="fa-solid fa-wand-magic-sparkles text-warning me-2"></i> Generator AI Modul</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("activities.*") ? "active" : "" }}" href="{{ route("activities.index") }}"><i class="fa-solid fa-calendar-days me-1 text-primary"></i> Agenda Kegiatan</a></li>
                        @if(in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs("documentations.*") ? "active" : "" }}" href="{{ route("documentations.index") }}"><i class="fa-solid fa-camera-retro me-1 text-warning"></i> Dokumentasi</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("announcements.*") ? "active" : "" }}" href="{{ route("announcements.index") }}"><i class="fa-solid fa-bullhorn me-1 text-danger"></i> Pengumuman</a></li>

                        @if(in_array(auth()->user()->role ?? '', ['superadmin', 'admin']))
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs("users.*") ? "active" : "" }}" href="{{ route("users.index") }}"><i class="fa-solid fa-user-gear me-1 text-light"></i> Kelola Akun</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route("home") }}#profil"><i class="fa-solid fa-info-circle me-1"></i> Profil MGMP</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route("home") }}#kegiatan"><i class="fa-solid fa-calendar-alt me-1"></i> Kegiatan</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("documentations.*") ? "active" : "" }}" href="{{ route("documentations.index") }}"><i class="fa-solid fa-camera-retro me-1"></i> Dokumentasi</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("questions.*") ? "active" : "" }}" href="{{ route("questions.index") }}"><i class="fa-solid fa-list-check me-1"></i> Bank Soal</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("materials.*") ? "active" : "" }}" href="{{ route("materials.index") }}"><i class="fa-solid fa-book-open-reader me-1"></i> Bahan Ajar Digital</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs("generator.*") ? "active" : "" }}" href="{{ route("generator.index") }}"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generator AI</a></li>
                    @endauth
                </ul>

                <!-- USER PROFILE HEADER DROPDOWN -->
                <div class="d-flex align-items-center gap-3 ms-auto ms-xl-0">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-link text-decoration-none text-white dropdown-toggle d-flex align-items-center gap-2 p-1 pe-2 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-10 shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(auth()->user()->member && auth()->user()->member->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->member->foto) }}" alt="{{ auth()->user()->name }}" class="rounded-circle object-fit-cover border border-white" style="width: 34px; height: 34px;">
                                @else
                                    <div class="bg-primary text-white rounded-circle fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; font-size: 0.9rem;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="d-none d-md-flex flex-column text-start me-1">
                                    <span class="fw-bold small lh-1 text-white">{{ Str::limit(auth()->user()->name, 16) }}</span>
                                    <span class="extra-small text-white-50 text-capitalize mt-1" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-shield-halved me-1 text-warning"></i>{{ auth()->user()->role }}
                                    </span>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 mt-2" style="min-width: 220px;">
                                <li class="px-3 py-2 border-bottom mb-2">
                                    <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                                    <div class="text-muted extra-small">{{ auth()->user()->email }}</div>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 mt-1 extra-small text-uppercase">{{ auth()->user()->role }}</span>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <i class="fa-solid fa-gauge text-primary me-2"></i> Dashboard
                                    </a>
                                </li>
                                @if(in_array(auth()->user()->role ?? '', ['superadmin', 'admin']))
                                    <li>
                                        <a class="dropdown-item rounded-3 py-2 px-3 {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                            <i class="fa-solid fa-user-gear text-success me-2"></i> Kelola Akun Pengguna
                                        </a>
                                    </li>
                                @endif
                                <li><a class="dropdown-item rounded-3 py-2 px-3" href="{{ route('home') }}"><i class="fa-solid fa-globe text-info me-2"></i> Halaman Depan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route("logout") }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-3 py-2 px-3 text-danger fw-semibold">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout / Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route("login") }}" class="btn btn-sm btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login Anggota
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @if (session("success"))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session("success") }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session("error"))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session("error") }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield("content")
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted fs-7 mt-auto">
        <div class="container">
            <small>&copy; {{ date("Y") }} SIM-MGMP Informatika SMP Kabupaten Ciamis — Sistem Informasi Manajemen Terpadu</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack("scripts")
</body>
</html>