@extends("layouts.app")

@section("content")
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-school text-primary me-2"></i> Data Sekolah SMP</h3>
        <p class="text-muted mb-0">Daftar sekolah SMP Negeri & Swasta terdaftar di Kabupaten Ciamis (Total {{ $schools->total() }} Sekolah)</p>
    </div>
    @if(auth()->check() && in_array(auth()->user()->role ?? '', ["superadmin", "admin", "pengurus"]))
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSekolah">
            <i class="fa-solid fa-plus me-1"></i> Tambah Sekolah
        </button>
    @endif
</div>

<div class="card card-custom p-4 bg-white mb-4 shadow-sm">
    <form method="GET" action="{{ route("schools.index") }}" class="row g-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama Sekolah atau NPSN..." value="{{ request("search") }}">
        </div>
        <div class="col-md-3">
            <select name="komisariat" class="form-select">
                <option value="">-- Semua Komisariat --</option>
                @foreach ($komisariats as $kom)
                    <option value="{{ $kom }}" {{ request("komisariat") == $kom ? "selected" : "" }}>Komisariat {{ $kom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="kecamatan" class="form-select">
                <option value="">-- Semua Kecamatan --</option>
                @foreach ($kecamatans as $k)
                    <option value="{{ $k }}" {{ request("kecamatan") == $k ? "selected" : "" }}>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
        </div>
    </form>
</div>

<div class="card card-custom bg-white border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>NPSN</th>
                    <th>Nama Sekolah</th>
                    <th>Komisariat</th>
                    <th>Status</th>
                    <th>Kecamatan</th>
                    <th>Kepala Sekolah</th>
                    <th>Jumlah Guru MGMP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schools as $index => $s)
                    <tr>
                        <td>{{ $schools->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $s->npsn ?? "-" }}</span></td>
                        <td class="fw-bold text-dark">{{ $s->nama_sekolah }}</td>
                        <td>
                            @if($s->komisariat)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                    <i class="fa-solid fa-layer-group me-1"></i> Komisariat {{ $s->komisariat }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">-</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $s->status == "NEGERI" ? "bg-success-subtle text-success" : "bg-warning-subtle text-warning" }}">{{ $s->status }}</span></td>
                        <td>{{ $s->kecamatan }}</td>
                        <td>{{ $s->nama_kepsek ?? "-" }}</td>
                        <td><span class="badge bg-primary rounded-pill px-3">{{ $s->members_count }} Guru</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data sekolah terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $schools->withQueryString()->links() }}</div>
</div>

<!-- MODAL TAMBAH SEKOLAH -->
@if(auth()->check() && in_array(auth()->user()->role ?? '', ["superadmin", "admin", "pengurus"]))
<div class="modal fade" id="modalTambahSekolah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route("schools.store") }}">
            @csrf
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2"></i> Tambah Data Sekolah</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sekolah" class="form-control" placeholder="e.g. SMP Negeri 5 Ciamis" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Komisariat <span class="text-danger">*</span></label>
                            <select name="komisariat" class="form-select" required>
                                <option value="1">Komisariat 1</option>
                                <option value="2">Komisariat 2</option>
                                <option value="3">Komisariat 3</option>
                                <option value="4">Komisariat 4</option>
                                <option value="5">Komisariat 5</option>
                                <option value="6">Komisariat 6</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select">
                                <option value="NEGERI">NEGERI</option>
                                <option value="SWASTA">SWASTA</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                        <input type="text" name="kecamatan" class="form-control" placeholder="e.g. Ciamis" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">NPSN</label>
                        <input type="text" name="npsn" class="form-control" placeholder="e.g. 20211501">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_kepsek" class="form-control" placeholder="Nama Lengkap Kepsek">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection