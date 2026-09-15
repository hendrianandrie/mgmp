@extends("layouts.app")

@section("content")
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-user-gear text-primary me-2"></i> Kelola Akun Pengguna</h3>
        <p class="text-muted mb-0">Manajemen akun pengguna, hak akses (role), reset password, dan integrasi data anggota</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        <i class="fa-solid fa-user-plus me-1"></i> Tambah Akun Baru
    </button>
</div>

<!-- FILTER & SEARCH BAR -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('users.index') }}" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nama, username, email, atau no HP...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Role --</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    <option value="pengurus" {{ request('role') == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru Anggota</option>
                    <option value="tamu" {{ request('role') == 'tamu' ? 'selected' : '' }}>Tamu</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i></button>
                @if(request()->anyFilled(['q', 'role', 'status']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- USERS TABLE -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light border-bottom">
                <tr>
                    <th class="ps-4 py-3 text-secondary extra-small fw-bold text-uppercase">Pengguna</th>
                    <th class="py-3 text-secondary extra-small fw-bold text-uppercase">Anggota Terkait</th>
                    <th class="py-3 text-secondary extra-small fw-bold text-uppercase">Role / Hak Akses</th>
                    <th class="py-3 text-secondary extra-small fw-bold text-uppercase">Status</th>
                    <th class="pe-4 py-3 text-end text-secondary extra-small fw-bold text-uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $u->name }}</div>
                                    <div class="text-muted extra-small">
                                        <i class="fa-regular fa-envelope me-1"></i>{{ $u->email }}
                                        @if($u->username)
                                            <span class="ms-2 badge bg-light text-muted border">@ {{ $u->username }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($u->member)
                                <div class="fw-semibold text-dark small">
                                    <i class="fa-solid fa-id-card-clip text-primary me-1"></i>
                                    {{ ($u->member->gelar_depan ? $u->member->gelar_depan.' ' : '').$u->member->nama_lengkap.($u->member->gelar_belakang ? ', '.$u->member->gelar_belakang : '') }}
                                </div>
                                @if($u->member->school)
                                    <div class="text-muted extra-small"><i class="fa-solid fa-school me-1"></i>{{ $u->member->school->nama_sekolah }}</div>
                                @endif
                            @else
                                <span class="badge bg-light text-muted border font-monospace extra-small">Belum Ditautkan</span>
                            @endif
                        </td>
                        <td>
                            @if($u->role == 'superadmin')
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">Superadmin</span>
                            @elseif($u->role == 'pengurus')
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">Pengurus</span>
                            @elseif($u->role == 'guru')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Guru Anggota</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1">Tamu</span>
                            @endif
                        </td>
                        <td>
                            @if($u->is_active)
                                <span class="badge bg-success text-white rounded-pill px-3 py-1"><i class="fa-solid fa-check me-1"></i> Aktif</span>
                            @else
                                <span class="badge bg-danger text-white rounded-pill px-3 py-1"><i class="fa-solid fa-xmark me-1"></i> Nonaktif</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-2 align-items-center">
                                <!-- TOGGLE STATUS BUTTON -->
                                <form method="POST" action="{{ route('users.toggle', $u->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-circle" title="{{ $u->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}" {{ auth()->id() == $u->id ? 'disabled' : '' }}>
                                        <i class="fa-solid {{ $u->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>

                                <!-- EDIT MODAL TRIGGER -->
                                <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" title="Edit & Reset Password">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <!-- DELETE BUTTON -->
                                @if(auth()->id() != $u->id)
                                    <form method="POST" action="{{ route('users.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}? Action ini tidak dapat dibatalkan.')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Akun">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL EDIT USER -->
                    <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <form method="POST" action="{{ route('users.update', $u->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-pen text-primary me-2"></i> Edit Akun & Reset Password</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 text-start">
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" name="name" value="{{ $u->name }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Username</label>
                                                <input type="text" name="username" value="{{ $u->username }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" value="{{ $u->email }}" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Role / Hak Akses <span class="text-danger">*</span></label>
                                                <select name="role" class="form-select" required>
                                                    <option value="superadmin" {{ $u->role == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                                    <option value="pengurus" {{ $u->role == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                                    <option value="guru" {{ $u->role == 'guru' ? 'selected' : '' }}>Guru Anggota</option>
                                                    <option value="tamu" {{ $u->role == 'tamu' ? 'selected' : '' }}>Tamu</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Tautkan dengan Profil Anggota MGMP</label>
                                            <select name="member_id" class="form-select">
                                                <option value="">-- Pilih Anggota MGMP (Opsional) --</option>
                                                @foreach($members as $m)
                                                    <option value="{{ $m->id }}" {{ $u->member && $u->member->id == $m->id ? 'selected' : '' }}>
                                                        {{ ($m->gelar_depan ? $m->gelar_depan.' ' : '').$m->nama_lengkap.($m->gelar_belakang ? ', '.$m->gelar_belakang : '') }}
                                                        {{ $m->school ? ' - '.$m->school->nama_sekolah : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 p-3 bg-light rounded-3">
                                            <label class="form-label small fw-semibold text-dark mb-1"><i class="fa-solid fa-key text-warning me-1"></i> Reset Password (Opsional)</label>
                                            <input type="password" name="password" class="form-control" placeholder="Isi password baru jika ingin mereset password...">
                                            <div class="form-text extra-small text-muted">Biarkan kosong jika tidak ingin mengubah password akun ini.</div>
                                        </div>

                                        <div class="form-check form-switch mt-3">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="switchActive{{ $u->id }}" {{ $u->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold small" for="switchActive{{ $u->id }}">Status Akun Aktif</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-users-slash fa-3x mb-3 opacity-50"></i>
                            <div>Tidak ada akun pengguna yang ditemukan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<div class="mt-3 d-flex justify-content-center">
    {{ $users->withQueryString()->links() }}
</div>

<!-- MODAL TAMBAH USER BARU -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus text-primary me-2"></i> Tambah Akun Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Drs. Ahmad Hidayat, M.Pd" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: ahmad.hidayat (Opsional)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="ahmad@gmail.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Role / Hak Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="guru" selected>Guru Anggota</option>
                                <option value="pengurus">Pengurus</option>
                                <option value="superadmin">Superadmin</option>
                                <option value="tamu">Tamu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tautkan dengan Profil Anggota MGMP</label>
                            <select name="member_id" class="form-select">
                                <option value="">-- Pilih Anggota MGMP (Opsional) --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">
                                        {{ ($m->gelar_depan ? $m->gelar_depan.' ' : '').$m->nama_lengkap.($m->gelar_belakang ? ', '.$m->gelar_belakang : '') }}
                                        {{ $m->school ? ' - '.$m->school->nama_sekolah : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="switchActiveNew" checked>
                        <label class="form-check-label fw-semibold small" for="switchActiveNew">Status Akun Langsung Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-save me-1"></i> Buat Akun Pengguna
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
