@extends("layouts.app")

@section("content")
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-calendar-days text-primary me-2"></i> Agenda & Kegiatan MGMP</h3>
        <p class="text-muted mb-0">Manajemen jadwal agenda dan kegiatan organisasi</p>
    </div>
    @if(in_array(auth()->user()->role, ["superadmin", "admin", "pengurus"]))
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKegiatan">
            <i class="fa-solid fa-plus me-1"></i> Buat Agenda
        </button>
    @endif
</div>

<div class="row g-4">
    @forelse($activities as $act)
        <div class="col-md-6">
            <div class="card card-custom p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">{{ $act->tanggal_kegiatan }}</span>
                    <span class="badge {{ $act->status == "BERJALAN" ? "bg-success" : ($act->status == "SELESAI" ? "bg-secondary" : "bg-warning text-dark") }}">{{ $act->status }}</span>
                </div>
                <h4 class="fw-bold text-dark mb-2">{{ $act->nama_kegiatan }}</h4>
                <p class="text-muted small mb-3"><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $act->lokasi }} | <i class="fa-solid fa-clock text-warning me-1"></i> {{ $act->waktu_mulai }} - {{ $act->waktu_selesai }} WIB</p>
                <p class="small text-secondary mb-4">{{ Str::limit($act->deskripsi, 120) }}</p>
                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                    <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-user me-1"></i> {{ $act->narasumber ?? 'Narasumber MGMP' }}</span>
                    <div class="d-flex align-items-center gap-1">
                        <a href="{{ route("activities.show", $act->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3" title="Lihat Detail">Detail <i class="fa-solid fa-arrow-right ms-1"></i></a>
                        @if(in_array(auth()->user()->role, ["superadmin", "admin", "pengurus"]))
                            <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEditKegiatan{{ $act->id }}" title="Edit Agenda">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <form method="POST" action="{{ route("activities.destroy", $act->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda kegiatan ini?')">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Hapus Agenda">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ["superadmin", "admin", "pengurus"]))
            <!-- MODAL EDIT KEGIATAN -->
            <div class="modal fade" id="modalEditKegiatan{{ $act->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route("activities.update", $act->id) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-content rounded-4 border-0">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Agenda Kegiatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-start">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nama Kegiatan</label>
                                    <input type="text" name="nama_kegiatan" class="form-control" value="{{ $act->nama_kegiatan }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Tanggal Kegiatan</label>
                                    <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ $act->tanggal_kegiatan }}" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Waktu Mulai</label>
                                        <input type="time" name="waktu_mulai" class="form-control" value="{{ $act->waktu_mulai }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold">Waktu Selesai</label>
                                        <input type="time" name="waktu_selesai" class="form-control" value="{{ $act->waktu_selesai }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Lokasi Tempat</label>
                                    <input type="text" name="lokasi" class="form-control" value="{{ $act->lokasi }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Narasumber</label>
                                    <input type="text" name="narasumber" class="form-control" value="{{ $act->narasumber }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai agenda kegiatan...">{{ $act->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Status Kegiatan</label>
                                    <select name="status" class="form-select">
                                        <option value="BERJALAN" {{ $act->status == "BERJALAN" ? "selected" : "" }}>BERJALAN</option>
                                        <option value="DRAFT" {{ $act->status == "DRAFT" ? "selected" : "" }}>DRAFT</option>
                                        <option value="SELESAI" {{ $act->status == "SELESAI" ? "selected" : "" }}>SELESAI</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning text-dark px-4">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @empty
        <div class="col-12 text-center text-muted py-5">Belum ada agenda kegiatan MGMP terdaftar.</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $activities->links() }}
</div>

<!-- MODAL TAMBAH KEGIATAN -->
<div class="modal fade" id="modalTambahKegiatan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route("activities.store") }}">
            @csrf
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-calendar-plus text-primary me-2"></i> Buat Agenda Kegiatan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="e.g. Workshop Pembelajaran Informatika" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ date("Y-m-d") }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" value="08:00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" value="15:00" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Lokasi Tempat</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="e.g. Aula SMPN 1 Ciamis" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Narasumber</label>
                        <input type="text" name="narasumber" class="form-control" placeholder="e.g. Dr. H. Maman, M.Pd.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai agenda kegiatan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Kegiatan</label>
                        <select name="status" class="form-select">
                            <option value="BERJALAN">BERJALAN</option>
                            <option value="DRAFT">DRAFT</option>
                            <option value="SELESAI">SELESAI</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Agenda</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection