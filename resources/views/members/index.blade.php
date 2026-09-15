@extends("layouts.app")

@section("content")
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-users text-primary me-2"></i> Data Anggota Guru MGMP</h3>
        <p class="text-muted mb-0">Kelola data guru pengampu mata pelajaran Informatika SMP se-Kabupaten Ciamis</p>
    </div>
    @if(auth()->check() && in_array(auth()->user()->role ?? '', ["superadmin", "admin", "pengurus"]))
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">
            <i class="fa-solid fa-user-plus me-1"></i> Tambah Anggota Guru
        </button>
    @endif
</div>

<div class="card card-custom p-4 bg-white mb-4 shadow-sm">
    <form method="GET" action="{{ route("members.index") }}" class="row g-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama, NIP, atau NUPTK..." value="{{ request("search") }}">
        </div>
        <div class="col-md-4">
            <select name="school_id" class="form-select">
                <option value="">-- Semua Sekolah --</option>
                @foreach ($schools as $s)
                    <option value="{{ $s->id }}" {{ request("school_id") == $s->id ? "selected" : "" }}>{{ $s->nama_sekolah }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
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
                    <th>Guru</th>
                    <th>NIP / NUPTK</th>
                    <th>Asal Sekolah</th>
                    <th>Status</th>
                    <th>Akun Login</th>
                    @if(auth()->check() && in_array(auth()->user()->role ?? '', ["superadmin", "admin", "pengurus"]))
                        <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($members as $index => $m)
                    <tr>
                        <td>{{ $members->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($m->foto)
                                    <img src="{{ asset('storage/' . $m->foto) }}" alt="{{ $m->nama_lengkap }}" class="rounded-circle object-fit-cover me-3 border shadow-sm" style="width: 44px; height: 44px;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 44px; height: 44px; font-size: 1rem;">
                                        {{ strtoupper(substr($m->nama_lengkap, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $m->gelar_depan }} {{ $m->nama_lengkap }} {{ $m->gelar_belakang }}</div>
                                    <small class="text-muted"><i class="fa-solid fa-graduation-cap me-1"></i> {{ $m->mapel_diampu ?? 'Informatika' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><small class="text-muted">NIP: {{ $m->nip ?? "-" }}<br>NUPTK: {{ $m->nuptk ?? "-" }}</small></td>
                        <td>{{ $m->school ? $m->school->nama_sekolah : "-" }}</td>
                        <td><span class="badge bg-info-subtle text-info">{{ $m->status_kepegawaian }}</span></td>
                        <td>
                            @if($m->user)
                                <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-user-check me-1"></i> {{ $m->user->username }}</span>
                            @else
                                <span class="badge bg-light text-muted border">Belum Ada Akun</span>
                            @endif
                        </td>
                        @if(auth()->check() && in_array(auth()->user()->role ?? '', ["superadmin", "admin", "pengurus"]))
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-warning rounded-pill px-3 me-1 btn-edit-member"
                                        data-id="{{ $m->id }}"
                                        data-user_id="{{ $m->user_id }}"
                                        data-nama_lengkap="{{ $m->nama_lengkap }}"
                                        data-gelar_depan="{{ $m->gelar_depan }}"
                                        data-gelar_belakang="{{ $m->gelar_belakang }}"
                                        data-nip="{{ $m->nip }}"
                                        data-nuptk="{{ $m->nuptk }}"
                                        data-school_id="{{ $m->school_id }}"
                                        data-jenis_kelamin="{{ $m->jenis_kelamin }}"
                                        data-status_kepegawaian="{{ $m->status_kepegawaian }}"
                                        data-username="{{ $m->user ? $m->user->username : "" }}"
                                        data-email="{{ $m->user ? $m->user->email : "" }}"
                                        data-foto="{{ $m->foto ? asset('storage/' . $m->foto) : '' }}"
                                        data-bs-toggle="modal" data-bs-target="#modalEditAnggota">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger rounded-pill px-3 btn-delete-member"
                                        data-id="{{ $m->id }}"
                                        data-nama="{{ $m->nama_lengkap }}"
                                        data-action="{{ route("members.destroy", $m->id) }}"
                                        data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHapus">
                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ (auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus'])) ? '7' : '6' }}" class="text-center text-muted py-4">Belum ada data anggota terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $members->withQueryString()->links() }}</div>
</div>

<!-- MODAL TAMBAH ANGGOTA -->
@if(auth()->check() && in_array(auth()->user()->role ?? '', ["superadmin", "admin", "pengurus"]))
<div class="modal fade" id="modalTambahAnggota" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route("members.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2"></i> Tambah Anggota Guru Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Gelar Depan</label>
                            <input type="text" name="gelar_depan" class="form-control" placeholder="e.g. Drs.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama tanpa gelar" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" class="form-control" placeholder="e.g. M.Pd.">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><i class="fa-solid fa-image text-primary me-1"></i> Foto Profil Guru</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted fs-7">Format yang didukung: JPG, PNG, WEBP. Maksimal 2MB.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIP</label>
                            <input type="text" name="nip" class="form-control" placeholder="NIP (opsional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NUPTK</label>
                            <input type="text" name="nuptk" class="form-control" placeholder="NUPTK (opsional)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Sekolah</label>
                            <select name="school_id" class="form-select">
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($schools as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Status Kepegawaian</label>
                            <select name="status_kepegawaian" class="form-select">
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="GTT">GTT</option>
                                <option value="Honorer">Honorer</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-key text-warning me-2"></i> Pengaturan Akun Login (Opsional)</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username Login</label>
                            <input type="text" name="username" class="form-control" placeholder="e.g. yunikartika">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password (Default: <code>password</code>)</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong untuk password default">
                        </div>
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

<!-- MODAL EDIT ANGGOTA -->
<div class="modal fade" id="modalEditAnggota" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route("members.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-pen me-2"></i> Edit Data Anggota Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Gelar Depan</label>
                            <input type="text" name="gelar_depan" id="edit_gelar_depan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" id="edit_nama_lengkap" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" id="edit_gelar_belakang" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold"><i class="fa-solid fa-image text-primary me-1"></i> Foto Profil Guru</label>
                        <div class="d-flex align-items-center gap-3">
                            <div id="edit_foto_preview_container" class="d-none">
                                <img id="edit_foto_preview" src="" alt="Preview Foto" class="rounded-circle object-fit-cover border shadow-sm" style="width: 54px; height: 54px;">
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted fs-7">Biarkan kosong jika tidak ingin mengubah foto saat ini.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIP</label>
                            <input type="text" name="nip" id="edit_nip" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NUPTK</label>
                            <input type="text" name="nuptk" id="edit_nuptk" class="form-control">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Sekolah</label>
                            <select name="school_id" id="edit_school_id" class="form-select">
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach ($schools as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_sekolah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="edit_jenis_kelamin" class="form-select">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Status Kepegawaian</label>
                            <select name="status_kepegawaian" id="edit_status_kepegawaian" class="form-select">
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="GTT">GTT</option>
                                <option value="Honorer">Honorer</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-key text-warning me-2"></i> Pengaturan Akun Login</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username Login</label>
                            <input type="text" name="username" id="edit_username" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password Baru (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin mengubah password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4">Update Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i> Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="p-3 bg-danger-subtle text-danger rounded-circle d-inline-block mb-3 fs-2" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-trash"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0">Anda akan menghapus data anggota guru <strong id="delete_nama_target" class="text-dark"></strong> beserta akun login-nya.</p>
                <small class="text-danger d-block mt-2"><i class="fa-solid fa-circle-info me-1"></i> Tindakan ini tidak dapat dibatalkan!</small>
            </div>
            <div class="modal-footer border-0 justify-content-center bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <form id="formDeleteMember" method="POST" action="">
                    @csrf
                    @method("DELETE")
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-trash me-1"></i> Ya, Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push("scripts")
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Edit Member modal population
        const editButtons = document.querySelectorAll(".btn-edit-member");
        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit_id").value = this.dataset.id || "";
                document.getElementById("edit_user_id").value = this.dataset.user_id || "";
                document.getElementById("edit_nama_lengkap").value = this.dataset.nama_lengkap || "";
                document.getElementById("edit_gelar_depan").value = this.dataset.gelar_depan || "";
                document.getElementById("edit_gelar_belakang").value = this.dataset.gelar_belakang || "";
                document.getElementById("edit_nip").value = this.dataset.nip || "";
                document.getElementById("edit_nuptk").value = this.dataset.nuptk || "";
                document.getElementById("edit_school_id").value = this.dataset.school_id || "";
                document.getElementById("edit_jenis_kelamin").value = this.dataset.jenis_kelamin || "L";
                document.getElementById("edit_status_kepegawaian").value = this.dataset.status_kepegawaian || "PNS";
                document.getElementById("edit_username").value = this.dataset.username || "";

                const fotoUrl = this.dataset.foto || "";
                const previewContainer = document.getElementById("edit_foto_preview_container");
                const previewImg = document.getElementById("edit_foto_preview");

                if (fotoUrl) {
                    previewImg.src = fotoUrl;
                    previewContainer.classList.remove("d-none");
                } else {
                    previewImg.src = "";
                    previewContainer.classList.add("d-none");
                }
            });
        });

        // Delete Member modal confirmation
        const deleteButtons = document.querySelectorAll(".btn-delete-member");
        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const nama = this.dataset.nama || "Anggota";
                const action = this.dataset.action || "";
                document.getElementById("delete_nama_target").innerText = nama;
                document.getElementById("formDeleteMember").action = action;
            });
        });
    });
</script>
@endpush
@endsection