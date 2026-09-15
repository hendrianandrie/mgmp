@extends("layouts.app")

@section("content")
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-sitemap text-primary me-2"></i> Struktur Kepengurusan MGMP</h3>
        <p class="text-muted mb-0">Pengurus MGMP Informatika SMP Kabupaten Ciamis Periode 2024-2027</p>
    </div>
    @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="openModalPosition()">
            <i class="fa-solid fa-plus me-1"></i> Tambah Jabatan / Pengurus
        </button>
    @endif
</div>

<div class="row g-4">
    @forelse ($positions as $pos)
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom p-4 bg-white text-center h-100 position-relative shadow-sm">
                @if($pos->member && $pos->member->foto)
                    <img src="{{ asset('storage/' . $pos->member->foto) }}" alt="{{ $pos->member->nama_dengan_gelar }}" class="rounded-circle object-fit-cover mx-auto mb-3 border shadow-sm" style="width: 84px; height: 84px;">
                @elseif($pos->member)
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold fs-3 shadow-sm" style="width: 84px; height: 84px;">
                        {{ strtoupper(substr($pos->member->nama_lengkap, 0, 1)) }}
                    </div>
                @else
                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fs-3" style="width: 84px; height: 84px;">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                @endif

                <h5 class="fw-bold text-dark mb-1">{{ $pos->nama_jabatan }}</h5>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-1 mb-3">Periode {{ $pos->periode }}</span>
                <p class="fw-semibold text-primary mb-0 fs-6">
                    {{ $pos->member ? $pos->member->nama_dengan_gelar : "- Belum Ditentukan -" }}
                </p>
                <small class="text-muted d-block mt-1">{{ $pos->member && $pos->member->school ? $pos->member->school->nama_sekolah : "Pilih anggota untuk mengisi posisi ini" }}</small>
                
                @if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
                    <div class="mt-3 pt-3 border-top">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" 
                                onclick='openModalPosition({{ json_encode($pos) }})'>
                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit / Tetapkan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="fa-solid fa-sitemap fs-1 mb-3 text-secondary"></i>
            <h6>Belum Ada Data Struktur Pengurus</h6>
            <p class="small">Klik tombol <strong>Tambah Jabatan / Pengurus</strong> di atas untuk membuat struktur organisasi baru.</p>
        </div>
    @endforelse
</div>

<!-- MODAL FORM POSITION -->
@if(auth()->check() && in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'pengurus']))
<div class="modal fade" id="modalPosition" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalPositionTitle"><i class="fa-solid fa-user-tie me-2"></i> Form Pengurus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf
                <input type="hidden" id="pos_id" name="id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Jabatan <span class="text-danger">*</span></label>
                        <input type="text" id="pos_nama_jabatan" name="nama_jabatan" class="form-control" placeholder="e.g. Ketua MGMP, Sekretaris, Bendahara, Koordinator Bidang" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Anggota Guru / Pejabat</label>
                        <select id="pos_member_id" name="member_id" class="form-select">
                            <option value="">-- Belum Ditentukan / Kosongkan --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}">{{ $m->nama_dengan_gelar }} ({{ $m->school ? $m->school->nama_sekolah : 'Tanpa Sekolah' }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted fs-7">Pilih dari daftar anggota guru yang terdaftar dalam SIM-MGMP.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Periode Jabatan</label>
                            <input type="text" id="pos_periode" name="periode" class="form-control" value="2024-2027" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Urutan Tampilan</label>
                            <input type="number" id="pos_urutan" name="urutan" class="form-control" value="1" min="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModalPosition(pos = null) {
        const modalEl = document.getElementById('modalPosition');
        const modal = new bootstrap.Modal(modalEl);
        
        if (pos) {
            document.getElementById('modalPositionTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2"></i> Edit Jabatan / Pengurus';
            document.getElementById('pos_id').value = pos.id;
            document.getElementById('pos_nama_jabatan').value = pos.nama_jabatan;
            document.getElementById('pos_member_id').value = pos.member_id || '';
            document.getElementById('pos_periode').value = pos.periode || '2024-2027';
            document.getElementById('pos_urutan').value = pos.urutan || 1;
        } else {
            document.getElementById('modalPositionTitle').innerHTML = '<i class="fa-solid fa-user-tie me-2"></i> Tambah Jabatan / Pengurus Baru';
            document.getElementById('pos_id').value = '';
            document.getElementById('pos_nama_jabatan').value = '';
            document.getElementById('pos_member_id').value = '';
            document.getElementById('pos_periode').value = '2024-2027';
            document.getElementById('pos_urutan').value = 1;
        }
        modal.show();
    }
</script>
@endpush
@endif
@endsection