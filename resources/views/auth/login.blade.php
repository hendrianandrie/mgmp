<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM-MGMP Informatika SMP Kab. Ciamis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #3b82f6 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-card { background: rgba(255,255,255,0.98); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35); max-width: 900px; width: 100%; overflow: hidden; }
        .stats-banner { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); color: #fff; padding: 40px; }
    </style>
</head>
<body>
    <div class="login-card card border-0">
        <div class="row g-0">
            <div class="col-lg-6 stats-banner d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fa-solid fa-laptop-code fs-3 text-warning"></i>
                        <h4 class="fw-bold mb-0">SIM-MGMP INFORMATIKA</h4>
                    </div>
                    <p class="text-white-50 fs-6">SMP KABUPATEN CIAMIS</p>
                    <hr class="border-white-20 my-4">
                    <p class="small text-white-80">Pusat manajemen terpadu keanggotaan guru, agenda kegiatan organisasi, bahan ajar digital, dan bank soal Informatika SMP.</p>
                </div>
            </div>
            <div class="col-lg-6 p-4 p-lg-5 bg-white">
                <h4 class="fw-bold text-dark mb-1">Masuk Sistem</h4>
                <p class="text-muted small mb-4">Masukkan username atau email Anda untuk masuk</p>

                @if (session("error"))
                    <div class="alert alert-danger small border-0 shadow-sm mb-3">{{ session("error") }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger small border-0 shadow-sm mb-3">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route("login.post") }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Username atau Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                            <input type="text" name="login" class="form-control border-start-0 ps-0" placeholder="e.g. admin atau yuni" value="{{ old("login") }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm fw-semibold">
                        <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Aplikasi
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route("home") }}" class="text-decoration-none small text-muted"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Portal Publik</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>