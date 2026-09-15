@extends("layouts.app")

@section("content")
<div class="mb-4">
    <a href="{{ route("activities.index") }}" class="btn btn-outline-secondary btn-sm rounded-pill mb-3"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Kegiatan</a>
    <h3 class="fw-bold mb-1">{{ $activity->nama_kegiatan }}</h3>
    <p class="text-muted mb-0"><i class="fa-solid fa-location-dot me-1"></i> {{ $activity->lokasi }} | {{ $activity->tanggal_kegiatan }}</p>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card card-custom p-4 bg-white text-center">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-qrcode text-primary me-2"></i> QR Code Presensi Digital</h5>
            <div class="p-3 bg-light rounded-4 d-inline-block mx-auto mb-3 border">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode(route("presensi.scan", $activity->qr_code_token)) }}" alt="QR Code Presensi" class="img-fluid">
            </div>
            <p class="small text-muted mb-3">Tampilkan QR Code ini di layar/proyektor agar peserta guru dapat melakukan scan presensi secara mandiri dari HP.</p>
            <a href="{{ route("presensi.scan", $activity->qr_code_token) }}" class="btn btn-success w-100 rounded-pill fw-semibold"><i class="fa-solid fa-check me-1"></i> Simulasi Presensi (Scan Direct)</a>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-custom p-4 bg-white">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-users-check text-success me-2"></i> Rekap Peserta Hadir ({{ $activity->attendances->count() }} Guru)</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Guru</th>
                            <th>Asal Sekolah</th>
                            <th>Waktu Presensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activity->attendances as $index => $att)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $att->member ? $att->member->nama_lengkap : "-" }}</td>
                                <td>{{ $att->member && $att->member->school ? $att->member->school->nama_sekolah : "-" }}</td>
                                <td><small class="text-muted">{{ $att->waktu_presensi }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada peserta yang melakukan presensi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection