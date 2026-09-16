@extends("layouts.app")

@section("content")
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-book-open-reader text-primary me-2"></i> Bahan Ajar Digital Informatika</h3>
        <p class="text-muted mb-0">Pusat tautan modul digital, media presentasi, LKPD interaktif, dan E-Book Kurikulum Merdeka</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('generator.index') }}" class="btn btn-warning rounded-pill px-4 shadow-sm fw-semibold">
            <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generator Modul AI
        </a>
        @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                <i class="fa-solid fa-plus-circle me-1"></i> Tambah Link Bahan Ajar
            </button>
        @endif
    </div>
</div>

<!-- BANNER GEMINI GENERATOR AI -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);">
    <div class="card-body p-4 text-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 mb-2 fw-semibold"><i class="fa-solid fa-sparkles me-1"></i> AI Module Generator</span>
                <h4 class="fw-bold mb-1">Buat Modul Ajar Digital dengan Gemini AI</h4>
                <p class="mb-0 text-white-80">Susun modul ajar Informatika sesuai Kurikulum Merdeka dengan mudah dan cepat secara otomatis.</p>
            </div>
            <a href="{{ route('generator.index') }}" class="btn btn-light text-primary rounded-pill px-4 fw-semibold shadow-sm text-nowrap">
                <i class="fa-solid fa-robot me-1"></i> Generator AI Sekarang
            </a>
        </div>
    </div>
</div>

<!-- FILTER & SEARCH BAR -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('materials.index') }}" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari judul bahan ajar atau deskripsi...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    <option value="7" {{ request('kelas') == '7' ? 'selected' : '' }}>Kelas 7</option>
                    <option value="8" {{ request('kelas') == '8' ? 'selected' : '' }}>Kelas 8</option>
                    <option value="9" {{ request('kelas') == '9' ? 'selected' : '' }}>Kelas 9</option>
                    <option value="Semua" {{ request('kelas') == 'Semua' ? 'selected' : '' }}>Semua Kelas</option>
                </select>
            </div>
            <div class="col-md-5">
                <select name="member_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Guru Penyusun --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_dengan_gelar }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i></button>
                @if(request()->anyFilled(['q', 'kelas', 'member_id']))
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- MATERIALS GRID -->
<div class="row g-4">
    @forelse($materials as $mat)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm bg-white rounded-4 overflow-hidden hover-top d-flex flex-column">
                
                <!-- COVER IMAGE / HEADER BANNER -->
                <div class="position-relative bg-dark overflow-hidden" style="height: 190px;">
                    @if($mat->sampul)
                        <img src="{{ asset('storage/' . $mat->sampul) }}" alt="{{ $mat->judul }}" class="w-100 h-100 object-fit-cover opacity-90 transition-zoom">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #1e293b 0%, #1e3a8a 50%, #2563eb 100%);">
                            <i class="fa-solid fa-book-open-reader fs-1 opacity-50"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-primary bg-opacity-90 text-white backdrop-blur rounded-pill px-3 py-1 extra-small">
                            Kelas {{ $mat->kelas }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <h5 class="fw-bold text-dark line-clamp-2 mb-2" title="{{ $mat->judul }}">
                        {{ $mat->judul }}
                    </h5>

                    <p class="text-secondary small flex-grow-1 mb-3 line-clamp-3">
                        {{ $mat->deskripsi ?? 'Bahan ajar digital terintegrasi untuk mendukung proses pembelajaran Informatika interaktif.' }}
                    </p>

                    <!-- IDENTITAS PENYUSUN -->
                    <div class="p-2 px-3 bg-light rounded-3 mb-3 small text-secondary">
                        <i class="fa-solid fa-user-pen text-primary me-1"></i>
                        <strong>Penyusun:</strong>
                        @if($mat->penyusun)
                            <span class="fw-semibold text-dark">
                                {{ $mat->penyusun->nama_dengan_gelar }}
                            </span>
                            @if($mat->penyusun->school)
                                <span class="d-block extra-small text-muted ps-3"><i class="fa-solid fa-school me-1"></i>{{ $mat->penyusun->school->nama_sekolah }}</span>
                            @endif
                        @else
                            <span class="fst-italic text-muted">Tim MGMP Informatika</span>
                        @endif
                    </div>

                    <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center gap-2">
                        @if($mat->link_external)
                            <a href="{{ $mat->link_external }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm rounded-pill px-3 flex-grow-1">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Link Bahan Ajar
                            </a>
                        @endif

                        @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#modalEditMateri{{ $mat->id }}" title="Edit Bahan Ajar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form method="POST" action="{{ route('materials.destroy', $mat->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tautan bahan ajar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2" title="Hapus Link">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
            <!-- MODAL EDIT BAHAN AJAR -->
            <div class="modal fade" id="modalEditMateri{{ $mat->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form method="POST" action="{{ route('materials.update', $mat->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header border-0 bg-light rounded-top-4">
                                <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Edit Bahan Ajar Digital</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Judul Bahan Ajar <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" class="form-control" value="{{ $mat->judul }}" required>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Target Kelas <span class="text-danger">*</span></label>
                                        <select name="kelas" class="form-select" required>
                                            <option value="7" {{ $mat->kelas == '7' ? 'selected' : '' }}>Kelas 7</option>
                                            <option value="8" {{ $mat->kelas == '8' ? 'selected' : '' }}>Kelas 8</option>
                                            <option value="9" {{ $mat->kelas == '9' ? 'selected' : '' }}>Kelas 9</option>
                                            <option value="Semua" {{ $mat->kelas == 'Semua' ? 'selected' : '' }}>Semua Kelas (7, 8, 9)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Penyusun / Author (Anggota MGMP)</label>
                                        <select name="member_id" class="form-select">
                                            <option value="">-- Pilih Penyusun --</option>
                                            @foreach($members as $m)
                                                <option value="{{ $m->id }}" {{ $mat->member_id == $m->id ? 'selected' : '' }}>
                                                    {{ $m->nama_dengan_gelar }}
                                                    {{ $m->school ? ' - '.$m->school->nama_sekolah : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Tautan / Link Eksternal <span class="text-danger">*</span></label>
                                    <input type="url" name="link_external" class="form-control" value="{{ $mat->link_external }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Ganti Gambar Sampul / Cover (Opsional)</label>
                                    <input type="file" name="sampul" class="form-control" accept="image/*">
                                    <small class="text-muted extra-small">Format: JPG, PNG, WEBP. Maks 5MB.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Deskripsi Singkat / Ringkasan Materi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3">{{ $mat->deskripsi }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-semibold">
                                    <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @empty
        <div class="col-12 py-5 text-center">
            <div class="bg-white rounded-4 p-5 shadow-sm">
                <i class="fa-solid fa-folder-open text-muted fa-4x mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark">Belum Ada Tautan Bahan Ajar Digital</h5>
                <p class="text-muted">Link bahan ajar belum diunggah oleh admin/pengurus atau tidak cocok dengan filter pencarian.</p>
                @if(request()->anyFilled(['q', 'kelas', 'member_id']))
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">Reset Filter</a>
                @endif
                @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahMateri">
                        <i class="fa-solid fa-plus-circle me-1"></i> Tambah Link Pertama
                    </button>
                @endif
            </div>
        </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-4 d-flex justify-content-center">
    {{ $materials->withQueryString()->links() }}
</div>

<!-- MODAL TAMBAH LINK BAHAN AJAR -->
@if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
<div class="modal fade" id="modalTambahMateri" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('materials.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-link me-2"></i> Tambah Link Bahan Ajar Digital</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Judul Bahan Ajar <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Slide Interaktif Canva - Berpikir Komputasional" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Target Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-select" required>
                                <option value="7">Kelas 7</option>
                                <option value="8">Kelas 8</option>
                                <option value="9">Kelas 9</option>
                                <option value="Semua" selected>Semua Kelas (7, 8, 9)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Penyusun / Author (Anggota MGMP)</label>
                            <select name="member_id" class="form-select">
                                <option value="">-- Pilih Penyusun --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">
                                        {{ $m->nama_dengan_gelar }}
                                        {{ $m->school ? ' - '.$m->school->nama_sekolah : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Gambar Sampul / Cover (Opsional)</label>
                        <input type="file" name="sampul" class="form-control" accept="image/*">
                        <small class="text-muted extra-small">Format: JPG, PNG, WEBP. Maks 5MB.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tautan / Link Eksternal <span class="text-danger">*</span></label>
                        <input type="url" name="link_external" class="form-control" placeholder="https://drive.google.com/... atau https://canva.com/..." required>
                        <div class="form-text">Masukkan URL lengkap menuju Google Drive, Canva, E-Book, YouTube, atau media interaktif.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi Singkat / Ringkasan Materi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan secara singkat topik pembelajaran atau panduan penggunaan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-save me-1"></i> Simpan Link Bahan Ajar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<style>
    .backdrop-blur { backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    .transition-zoom { transition: transform 0.4s ease; }
    .hover-top:hover .transition-zoom { transform: scale(1.06); }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection