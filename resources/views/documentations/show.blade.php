@extends("layouts.app")

@section("content")
<!-- BREADCRUMB & BACK BUTTON -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route("documentations.index") }}" class="btn btn-outline-secondary rounded-pill px-3 py-1 extra-small">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Semua Album
    </a>
    @auth
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahFoto">
            <i class="fa-solid fa-plus me-1"></i> Tambah Foto Ke Album
        </button>
    @endauth
</div>

<!-- ALBUM HEADER CARD -->
<div class="card card-custom p-4 bg-white mb-4 border-0 shadow-sm">
    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
        <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1">
            <i class="fa-solid fa-calendar-day me-1"></i> {{ \Carbon\Carbon::parse($album->tanggal)->format("d F Y") }}
        </span>
        @if($album->komisariat)
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                <i class="fa-solid fa-location-dot me-1"></i> {{ $album->komisariat }}
            </span>
        @endif
        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 ms-auto">
            <i class="fa-solid fa-camera me-1"></i> Total {{ $album->photos->count() }} Foto
        </span>
    </div>

    <h2 class="fw-bold text-dark mb-2">{{ $album->nama_album }}</h2>
    @if($album->activity)
        <p class="text-primary fw-semibold small mb-3">
            <i class="fa-solid fa-calendar-check me-1"></i> Tautan Agenda: <a href="{{ route("activities.show", $album->activity->id) }}" class="text-decoration-none text-primary">{{ $album->activity->nama_kegiatan }}</a>
        </p>
    @endif
    <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $album->deskripsi ?? 'Dokumentasi foto kegiatan resmi MGMP Informatika SMP Kabupaten Ciamis.' }}</p>

    <div class="mt-3 pt-3 border-top extra-small text-muted d-flex align-items-center gap-3">
        <span><i class="fa-solid fa-user-pen me-1 text-primary"></i> Diunggah oleh: {{ $album->uploader->name ?? 'Pengurus' }}</span>
        <span><i class="fa-solid fa-clock me-1 text-warning"></i> Dibuat pada: {{ $album->created_at->format("d M Y H:i") }} WIB</span>
    </div>
</div>

<!-- PHOTOS GALLERY GRID -->
<div class="row g-3">
    @forelse($album->photos as $photo)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm h-100 group-photo">
                <div class="position-relative bg-dark" style="height: 200px;">
                    <img src="{{ asset("storage/" . $photo->foto_path) }}" alt="{{ $photo->judul_foto ?? $album->nama_album }}" class="w-100 h-100 object-fit-cover transition-zoom cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalPreviewPhoto{{ $photo->id }}">
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <button type="button" class="btn btn-dark bg-opacity-75 text-white btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#modalPreviewPhoto{{ $photo->id }}" title="Lihat Ukuran Penuh">
                            <i class="fa-solid fa-expand fs-7"></i>
                        </button>
                        @auth
                            <form method="POST" action="{{ route("documentations.destroyPhoto", $photo->id) }}" onsubmit="return confirm('Hapus foto ini dari album?')">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger bg-opacity-90 btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Hapus Foto">
                                    <i class="fa-solid fa-trash fs-7"></i>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
                @if($photo->judul_foto || $photo->caption)
                    <div class="p-2 text-center bg-white extra-small">
                        <div class="fw-semibold text-dark text-truncate">{{ $photo->judul_foto }}</div>
                        @if($photo->caption)<div class="text-muted extra-small text-truncate">{{ $photo->caption }}</div>@endif
                    </div>
                @endif
            </div>
        </div>

        <!-- MODAL PREVIEW LIGHTBOX PHOTO -->
        <div class="modal fade" id="modalPreviewPhoto{{ $photo->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-dark text-white rounded-4 border-0 overflow-hidden">
                    <div class="modal-header border-0 pb-0">
                        <span class="small text-white-50">{{ $photo->judul_foto ?? $album->nama_album }}</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-2 text-center position-relative">
                        <img src="{{ asset("storage/" . $photo->foto_path) }}" alt="{{ $photo->judul_foto }}" class="img-fluid rounded-3" style="max-height: 80vh; object-fit: contain;">
                        @if($photo->caption)
                            <div class="p-3 text-white-80 small bg-black bg-opacity-50 mt-2 rounded-3 text-center">
                                {{ $photo->caption }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="p-4 bg-white rounded-4 card-custom d-inline-block text-muted px-5">
                <i class="fa-solid fa-image fs-1 mb-2 text-secondary"></i>
                <p class="small mb-0">Belum ada foto dalam album ini.</p>
            </div>
        </div>
    @endforelse
</div>

@auth
    <!-- MODAL TAMBAH FOTO KE ALBUM -->
    <div class="modal fade" id="modalTambahFoto" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route("documentations.storePhoto", $album->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-2"></i> Tambah Foto Ke Album</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Foto (Bisa Banyak File) <span class="text-danger">*</span></label>
                            <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
                            <small class="text-muted extra-small">Maksimal 5MB per file foto.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Judul/Label Foto (Opsional)</label>
                            <input type="text" name="judul_foto" class="form-control" placeholder="e.g. Sesi Pembukaan Workshop">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Keterangan Foto (Opsional)</label>
                            <textarea name="caption" class="form-control" rows="2" placeholder="Catatan singkat tentang foto..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Upload Foto</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endauth

<style>
    .transition-zoom { transition: transform 0.4s ease; }
    .group-photo:hover .transition-zoom { transform: scale(1.08); }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
