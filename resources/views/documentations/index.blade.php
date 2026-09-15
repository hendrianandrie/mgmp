@extends("layouts.app")

@section("content")
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark">
            <i class="fa-solid fa-camera-retro text-warning me-2"></i> Dokumentasi & Galeri Kegiatan
        </h3>
        <p class="text-muted mb-0">Arsip album foto dokumentasi kegiatan MGMP Informatika SMP Kabupaten Ciamis</p>
    </div>
    @auth
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahAlbum">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload Album Dokumentasi
        </button>
    @endauth
</div>

<!-- SEARCH BAR -->
<div class="card card-custom p-3 bg-white mb-4 shadow-sm border-0">
    <form method="GET" action="{{ route("documentations.index") }}" class="row g-2 align-items-center">
        <div class="col-md-9 col-lg-10">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="q" class="form-control bg-light border-0" placeholder="Cari nama album atau deskripsi kegiatan..." value="{{ request("q") }}">
            </div>
        </div>
        <div class="col-md-3 col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="fa-solid fa-magnifying-glass me-1"></i> Cari</button>
            @if(request()->filled("q"))
                <a href="{{ route("documentations.index") }}" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" title="Reset Pencarian"><i class="fa-solid fa-rotate-left"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- ALBUMS GRID -->
<div class="row g-4">
    @forelse($albums as $album)
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom h-100 bg-white overflow-hidden shadow-sm d-flex flex-column border-0">
                <div class="position-relative bg-dark overflow-hidden" style="height: 220px;">
                    @if($album->cover_foto)
                        <img src="{{ asset("storage/" . $album->cover_foto) }}" alt="{{ $album->nama_album }}" class="w-100 h-100 object-fit-cover opacity-90 transition-zoom">
                    @elseif($album->photos->first())
                        <img src="{{ asset("storage/" . $album->photos->first()->foto_path) }}" alt="{{ $album->nama_album }}" class="w-100 h-100 object-fit-cover opacity-90 transition-zoom">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-white-50">
                            <i class="fa-solid fa-images fs-1 mb-2"></i>
                            <span class="small font-monospace">Belum ada foto</span>
                        </div>
                    @endif

                    <div class="position-absolute top-0 start-0 m-3 d-flex gap-2 flex-wrap">
                        <span class="badge bg-dark bg-opacity-75 text-white backdrop-blur rounded-pill px-3 py-1 extra-small">
                            <i class="fa-solid fa-calendar-day text-warning me-1"></i> {{ \Carbon\Carbon::parse($album->tanggal)->format("d M Y") }}
                        </span>
                        @if($album->komisariat)
                            <span class="badge bg-primary bg-opacity-90 text-white rounded-pill px-3 py-1 extra-small">
                                <i class="fa-solid fa-location-dot me-1"></i> {{ $album->komisariat }}
                            </span>
                        @endif
                    </div>

                    <div class="position-absolute bottom-0 end-0 m-3">
                        <span class="badge bg-black bg-opacity-75 text-white backdrop-blur rounded-pill px-3 py-1 extra-small">
                            <i class="fa-solid fa-camera text-info me-1"></i> {{ $album->photos_count }} Foto
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2">{{ $album->nama_album }}</h5>
                    @if($album->activity)
                        <div class="extra-small text-primary fw-semibold mb-2">
                            <i class="fa-solid fa-calendar-check me-1"></i> {{ $album->activity->nama_kegiatan }}
                        </div>
                    @endif
                    <p class="card-text text-secondary small mb-4 flex-grow-1 line-clamp-3">
                        {{ $album->deskripsi ?? 'Dokumentasi foto kegiatan resmi MGMP Informatika SMP Kabupaten Ciamis.' }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                        <span class="extra-small text-muted">
                            <i class="fa-solid fa-user-pen me-1"></i> {{ $album->uploader->name ?? 'Pengurus' }}
                        </span>
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route("documentations.show", $album->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                Lihat Album <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                            @auth
                                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#modalEditAlbum{{ $album->id }}" title="Edit Album">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form method="POST" action="{{ route("documentations.destroy", $album->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus album dan semua foto di dalamnya?')">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2" title="Hapus Album">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <!-- MODAL EDIT ALBUM -->
            <div class="modal fade" id="modalEditAlbum{{ $album->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="{{ route("documentations.update", $album->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header border-0 bg-light rounded-top-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Album Dokumentasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nama Album Kegiatan</label>
                                    <input type="text" name="nama_album" class="form-control" value="{{ $album->nama_album }}" required>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Tautan Agenda Kegiatan MGMP (Opsional)</label>
                                        <select name="activity_id" class="form-select">
                                            <option value="">-- Tanpa Tautan Agenda --</option>
                                            @foreach($activities as $act)
                                                <option value="{{ $act->id }}" {{ $album->activity_id == $act->id ? 'selected' : '' }}>{{ $act->nama_kegiatan }} ({{ $act->tanggal_kegiatan }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Tanggal Kegiatan</label>
                                        <input type="date" name="tanggal" class="form-control" value="{{ $album->tanggal }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Komisariat / Wilayah</label>
                                    <input type="text" name="komisariat" class="form-control" value="{{ $album->komisariat }}" placeholder="e.g. Kabupaten Ciamis / Komisariat Kawali">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Deskripsi Album</label>
                                    <textarea name="deskripsi" class="form-control" rows="3">{{ $album->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Ganti Cover Album (Opsional)</label>
                                    <input type="file" name="cover_foto" class="form-control" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Tambah Foto Baru ke Album (Multiple)</label>
                                    <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-semibold">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endauth
    @empty
        <div class="col-12 text-center py-5">
            <div class="p-4 bg-white rounded-4 card-custom d-inline-block text-muted px-5">
                <i class="fa-solid fa-images fs-1 mb-3 text-warning"></i>
                <h5 class="fw-bold text-dark mb-1">Belum Ada Album Dokumentasi</h5>
                <p class="small text-secondary mb-3">Dokumentasi foto kegiatan MGMP belum diunggah.</p>
                @auth
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahAlbum">
                        <i class="fa-solid fa-plus me-1"></i> Upload Album Pertama
                    </button>
                @endauth
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $albums->links() }}
</div>

@auth
    <!-- MODAL TAMBAH ALBUM -->
    <div class="modal fade" id="modalTambahAlbum" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route("documentations.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload Album Dokumentasi Kegiatan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Album Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_album" class="form-control" placeholder="e.g. Workshop Modul Ajar Kurikulum Merdeka 2026" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tautan Agenda Kegiatan MGMP (Opsional)</label>
                                <select name="activity_id" class="form-select">
                                    <option value="">-- Tanpa Tautan Agenda --</option>
                                    @foreach($activities as $act)
                                        <option value="{{ $act->id }}">{{ $act->nama_kegiatan }} ({{ $act->tanggal_kegiatan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date("Y-m-d") }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Komisariat / Wilayah Penyelenggara</label>
                            <input type="text" name="komisariat" class="form-control" placeholder="e.g. Kabupaten Ciamis / Komisariat Kawali / Komisariat Rancah">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Deskripsi / Catatan Album</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat suasana dan hasil kegiatan..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Foto Sampul (Cover Album)</label>
                            <input type="file" name="cover_foto" class="form-control" accept="image/*">
                            <small class="text-muted extra-small">Format: JPG, PNG, WEBP. Maks 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Upload Foto-Foto Kegiatan (Bisa Banyak File)</label>
                            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
                            <small class="text-muted extra-small">Pilih beberapa foto sekaligus. Maks 5MB per file.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Upload Album</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endauth

<style>
    .backdrop-blur { backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    .transition-zoom { transition: transform 0.4s ease; }
    .card-custom:hover .transition-zoom { transform: scale(1.06); }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection
