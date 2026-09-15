@extends("layouts.app")

@section("content")
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-gauge-high text-primary me-2"></i> Dashboard SIM-MGMP</h3>
        <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong> (Role: {{ ucfirst(auth()->user()->role) }})</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <span class="badge bg-white text-dark border px-3 py-2 fs-6 rounded-pill shadow-sm">
            <i class="fa-regular fa-calendar-days text-primary me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat("l, d F Y") }}
        </span>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-3"><i class="fa-solid fa-school"></i></div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalSekolah }}</h3>
                    <small class="text-muted">Sekolah Terdaftar</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-4 fs-3"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalAnggota }}</h3>
                    <small class="text-muted">Guru Anggota</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-warning-subtle text-warning rounded-4 fs-3"><i class="fa-solid fa-calendar-check"></i></div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalKegiatan }}</h3>
                    <small class="text-muted">Total Kegiatan</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-info-subtle text-info rounded-4 fs-3"><i class="fa-solid fa-book-bookmark"></i></div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $totalMateri }}</h3>
                    <small class="text-muted">Bank Perangkat Ajar</small>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row g-4">
    <div class="col-lg-6">
        <div class="card card-custom p-4 bg-white h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-book text-primary me-2"></i> Perangkat Ajar Terbaru</h5>
            <div class="list-group list-group-flush">
                @foreach ($materiTerbaru as $mat)
                    <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold text-dark">{{ $mat->judul }}</div>
                            <small class="text-muted">Kelas {{ $mat->kelas }} | Kategori: {{ $mat->category ? $mat->category->nama_kategori : "-" }}</small>
                        </div>
                        <span class="badge bg-light text-dark border">{{ $mat->jumlah_download }} Download</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-custom p-4 bg-white h-100">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-bullhorn text-warning me-2"></i> Pengumuman Organisasi</h5>
            <div class="list-group list-group-flush">
                @foreach ($pengumumanTerbaru as $peng)
                    <div class="list-group-item px-0 py-2">
                        <div class="fw-semibold text-dark">{{ $peng->judul }}</div>
                        <small class="text-muted">{{ Str::limit($peng->isi_pengumuman, 100) }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection