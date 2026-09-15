@extends("layouts.app")

@section("content")
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-bullhorn text-warning me-2"></i> Pengumuman & Berita MGMP</h3>
        <p class="text-muted mb-0">Informasi dan pengumuman resmi organisasi</p>
    </div>
    @if(in_array(auth()->user()->role, ["superadmin", "admin", "pengurus"]))
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPengumuman">
            <i class="fa-solid fa-plus me-1"></i> Terbitkan Pengumuman
        </button>
    @endif
</div>

<div class="row g-4">
    @forelse($announcements as $p)
        <div class="col-12">
            <div class="card card-custom p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <span class="badge bg-warning-subtle text-dark rounded-pill px-3 py-1 me-2">{{ $p->created_at->format("d M Y") }}</span>
                        @if($p->author)
                            <span class="text-muted small"><i class="fa-solid fa-user me-1"></i> {{ $p->author->name }}</span>
                        @endif
                    </div>
                    @if(in_array(auth()->user()->role, ["superadmin", "admin", "pengurus"]))
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEditPengumuman{{ $p->id }}" title="Edit Pengumuman">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <form method="POST" action="{{ route("announcements.destroy", $p->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Hapus Pengumuman">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <h4 class="fw-bold text-dark mb-2">{{ $p->judul }}</h4>
                <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $p->isi_pengumuman }}</p>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ["superadmin", "admin", "pengurus"]))
            <!-- MODAL EDIT PENGUMUMAN -->
            <div class="modal fade" id="modalEditPengumuman{{ $p->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route("announcements.update", $p->id) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-content rounded-4 border-0">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Pengumuman</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-start">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Judul Pengumuman</label>
                                    <input type="text" name="judul" class="form-control" value="{{ $p->judul }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Isi Pengumuman</label>
                                    <textarea name="isi_pengumuman" class="form-control" rows="5" required>{{ $p->isi_pengumuman }}</textarea>
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
        <div class="col-12 text-center text-muted py-5">Belum ada pengumuman terbit.</div>
    @endforelse
</div>

<div class="mt-4">
    {{ $announcements->links() }}
</div>

<!-- MODAL TAMBAH PENGUMUMAN -->
<div class="modal fade" id="modalTambahPengumuman" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route("announcements.store") }}">
            @csrf
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-bullhorn text-warning me-2"></i> Terbitkan Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Judul Pengumuman</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul pengumuman..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Isi Pengumuman</label>
                        <textarea name="isi_pengumuman" class="form-control" rows="5" placeholder="Tulis isi pengumuman..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Terbitkan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection