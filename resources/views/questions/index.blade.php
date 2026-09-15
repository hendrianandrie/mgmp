@extends("layouts.app")

@section("content")
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-file-pdf text-danger me-2"></i> Bank Soal Informatika</h3>
        <p class="text-muted mb-0">Unduh berkas soal (.pdf) Informatika terstandar untuk SMP/MTs</p>
    </div>
    @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSoal">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Upload Soal PDF
        </button>
    @endif
</div>

<!-- FILTER & SEARCH BAR -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('questions.index') }}" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari judul soal atau deskripsi...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Kelas --</option>
                    <option value="7" {{ request('kelas') == '7' ? 'selected' : '' }}>Kelas 7</option>
                    <option value="8" {{ request('kelas') == '8' ? 'selected' : '' }}>Kelas 8</option>
                    <option value="9" {{ request('kelas') == '9' ? 'selected' : '' }}>Kelas 9</option>
                    <option value="7, 8, 9" {{ request('kelas') == '7, 8, 9' ? 'selected' : '' }}>Semua Kelas</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="jenis_soal" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Jenis Asesmen --</option>
                    <option value="Sumatif" {{ request('jenis_soal') == 'Sumatif' ? 'selected' : '' }}>Sumatif</option>
                    <option value="Formatif" {{ request('jenis_soal') == 'Formatif' ? 'selected' : '' }}>Formatif</option>
                    <option value="PAS" {{ request('jenis_soal') == 'PAS' ? 'selected' : '' }}>PAS (Akhir Semester)</option>
                    <option value="PAT" {{ request('jenis_soal') == 'PAT' ? 'selected' : '' }}>PAT (Akhir Tahun)</option>
                    <option value="Tryout" {{ request('jenis_soal') == 'Tryout' ? 'selected' : '' }}>Tryout</option>
                    <option value="Diagnostik" {{ request('jenis_soal') == 'Diagnostik' ? 'selected' : '' }}>Diagnostik</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="member_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Penyusun --</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" {{ request('member_id') == $m->id ? 'selected' : '' }}>
                            {{ ($m->gelar_depan ? $m->gelar_depan.' ' : '').$m->nama_lengkap.($m->gelar_belakang ? ', '.$m->gelar_belakang : '') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i></button>
                @if(request()->anyFilled(['q', 'kelas', 'jenis_soal', 'member_id']))
                    <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- BANK SOAL GRID -->
<div class="row g-4">
    @forelse($questions as $q)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 card-custom border-0 shadow-sm bg-white rounded-4 overflow-hidden position-relative hover-top">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 p-3 text-danger flex-shrink-0">
                            <i class="fa-solid fa-file-pdf fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex flex-wrap gap-1 mb-1">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 small">Kelas {{ $q->kelas }}</span>
                                <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 small">{{ $q->jenis_soal ?? 'Soal' }}</span>
                            </div>
                            <h6 class="fw-bold text-dark text-truncate-2 mb-0" title="{{ $q->judul ?? $q->materi_pokok ?? $q->soal_text }}">
                                {{ $q->judul ?? $q->materi_pokok ?? Str::limit($q->soal_text, 60) }}
                            </h6>
                        </div>
                    </div>

                    <p class="text-muted small flex-grow-1 mb-2">
                        {{ $q->deskripsi ?? $q->materi_pokok ?? 'Berkas soal naskah Informatika format PDF siap diunduh dan dicetak.' }}
                    </p>

                    <!-- IDENTITAS PENYUSUN -->
                    <div class="p-2 px-3 bg-light rounded-3 mb-3 small text-secondary">
                        <i class="fa-solid fa-user-pen text-primary me-1"></i>
                        <strong>Penyusun:</strong>
                        @if($q->penyusun)
                            <span class="fw-semibold text-dark">
                                {{ ($q->penyusun->gelar_depan ? $q->penyusun->gelar_depan.' ' : '').$q->penyusun->nama_lengkap.($q->penyusun->gelar_belakang ? ', '.$q->penyusun->gelar_belakang : '') }}
                            </span>
                            @if($q->penyusun->school)
                                <span class="d-block extra-small text-muted ps-3"><i class="fa-solid fa-school me-1"></i>{{ $q->penyusun->school->nama_sekolah }}</span>
                            @endif
                        @else
                            <span class="fst-italic text-muted">Tim Tim MGMP Informatika</span>
                        @endif
                    </div>

                    <div class="border-top pt-3 mt-auto">
                        <div class="d-flex justify-content-between align-items-center text-muted extra-small mb-3">
                            <span><i class="fa-solid fa-hard-drive me-1"></i>{{ $q->ukuran_file ?? 'PDF' }}</span>
                            <span><i class="fa-solid fa-download me-1 text-success"></i>{{ $q->jumlah_download }}x diunduh</span>
                            <span><i class="fa-solid fa-calendar me-1"></i>{{ $q->created_at ? $q->created_at->format('d M Y') : '-' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-2">
                            @if($q->file_path)
                                <a href="{{ route('questions.download', $q->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 flex-grow-1">
                                    <i class="fa-solid fa-download me-1"></i> Unduh PDF
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm rounded-pill px-3 flex-grow-1" disabled>Teks Soal</button>
                            @endif

                            @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
                                <form method="POST" action="{{ route('questions.destroy', $q->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas soal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Hapus Berkas">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5 text-center">
            <div class="bg-white rounded-4 p-5 shadow-sm">
                <i class="fa-solid fa-folder-open text-muted fa-4x mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark">Belum Ada Berkas Bank Soal</h5>
                <p class="text-muted">Berkas soal belum diunggah oleh pengurus atau tidak cocok dengan filter pencarian.</p>
                @if(request()->anyFilled(['q', 'kelas', 'jenis_soal', 'member_id']))
                    <a href="{{ route('questions.index') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">Reset Filter</a>
                @endif
                @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahSoal">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload PDF Pertama
                    </button>
                @endif
            </div>
        </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-4 d-flex justify-content-center">
    {{ $questions->withQueryString()->links() }}
</div>

<!-- MODAL UPLOAD SOAL PDF -->
@if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
<div class="modal fade" id="modalTambahSoal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('questions.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-circle-plus text-primary me-2"></i> Upload Berkas Soal Informatika (.PDF)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Judul Naskah Soal <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Soal Sumatif Akhir Semester (SAS) Ganjil Informatika Kelas 7" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Target Kelas <span class="text-danger">*</span></label>
                            <select name="kelas" class="form-select" required>
                                <option value="7">Kelas 7</option>
                                <option value="8">Kelas 8</option>
                                <option value="9">Kelas 9</option>
                                <option value="7, 8, 9">Semua Kelas (7, 8, 9)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jenis Asesmen <span class="text-danger">*</span></label>
                            <select name="jenis_soal" class="form-select" required>
                                <option value="Sumatif" selected>Sumatif</option>
                                <option value="Formatif">Formatif</option>
                                <option value="PAS">Penilaian Akhir Semester (PAS)</option>
                                <option value="PAT">Penilaian Akhir Tahun (PAT)</option>
                                <option value="Tryout">Tryout / Asesmen</option>
                                <option value="Diagnostik">Asesmen Diagnostik</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Penyusun / Author Soal (Anggota MGMP)</label>
                        <select name="member_id" class="form-select">
                            <option value="">-- Pilih Penyusun Soal --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}">
                                    {{ ($m->gelar_depan ? $m->gelar_depan.' ' : '').$m->nama_lengkap.($m->gelar_belakang ? ', '.$m->gelar_belakang : '') }}
                                    {{ $m->school ? ' - '.$m->school->nama_sekolah : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilih nama guru anggota yang menyusun paket soal ini.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Upload File PDF Soal <span class="text-danger">*</span></label>
                        <input type="file" name="file_pdf" class="form-control" accept=".pdf" required>
                        <div class="form-text">Format yang didukung: <strong>.pdf</strong> (Maksimal 20 MB).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi / Catatan Tambahan</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Informasi singkat kisi-kisi, penyusun, atau instruksi pengerjaan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Soal PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection