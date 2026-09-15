@extends("layouts.app")

@section("content")
<style>
    .nav-ai-tab {
        background-color: #f1f5f9;
        padding: 6px;
        border-radius: 14px;
    }
    .btn-tab-modul {
        color: #1e40af !important;
        background-color: #eff6ff;
        border: 1.5px solid #bfdbfe;
        transition: all 0.25s ease;
    }
    .btn-tab-modul.active, .btn-tab-modul:hover {
        color: #ffffff !important;
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
        border-color: transparent !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }
    
    .btn-tab-soal {
        color: #065f46 !important;
        background-color: #ecfdf5;
        border: 1.5px solid #a7f3d0;
        transition: all 0.25s ease;
    }
    .btn-tab-soal.active, .btn-tab-soal:hover {
        color: #ffffff !important;
        background: linear-gradient(135deg, #065f46 0%, #059669 100%) !important;
        border-color: transparent !important;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
    }
</style>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 mb-2 fw-semibold"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Powered by Gemini AI</span>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-robot text-primary me-2"></i> Generator AI Pembelajaran & Asesmen</h3>
        <p class="text-muted mb-0">Pilih menu generator di bawah: <strong>Modul Ajar</strong> atau <strong>Naskah Soal AI</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route("questions.index") }}" class="btn btn-outline-danger rounded-pill px-3"><i class="fa-solid fa-file-pdf me-1"></i> Bank Soal</a>
        <a href="{{ route("materials.index") }}" class="btn btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-book-open-reader me-1"></i> Bahan Ajar</a>
    </div>
</div>

<div class="row g-4">
    <!-- FORM INPUT GENERATOR -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 bg-white shadow-sm">
            <!-- TAB SELECTION BUTTONS -->
            <ul class="nav nav-pills nav-fill nav-ai-tab gap-2 mb-4" id="aiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn-tab-modul active rounded-3 py-2 px-3 fw-bold" id="tab-modul" data-bs-toggle="pill" data-bs-target="#content-modul" type="button" role="tab">
                        <i class="fa-solid fa-file-contract me-2"></i> 1. Modul Ajar AI
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn-tab-soal rounded-3 py-2 px-3 fw-bold" id="tab-soal" data-bs-toggle="pill" data-bs-target="#content-soal" type="button" role="tab">
                        <i class="fa-solid fa-list-check me-2"></i> 2. Naskah Soal AI
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="aiTabContent">
                <!-- TAB 1: FORM MODUL AJAR -->
                <div class="tab-pane fade show active" id="content-modul" role="tabpanel">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-contract fs-5"></i>
                        <span class="fw-bold">Form Parameter Modul Ajar AI</span>
                    </div>

                    <form id="formGenerateAi">
                        @csrf
                        <input type="hidden" name="api_key" value="{{ env('GEMINI_API_KEY', '') }}">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Kelas</label>
                                <select name="kelas" class="form-select">
                                    <option value="7">Kelas 7 SMP</option>
                                    <option value="8">Kelas 8 SMP</option>
                                    <option value="9">Kelas 9 SMP</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                <input type="text" name="tahun_ajaran" class="form-control" placeholder="e.g. 2025/2026" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Tujuan Pembelajaran (TP) <span class="text-danger">*</span></label>
                            <textarea name="tujuan_pembelajaran" class="form-control" rows="3" placeholder="e.g. Peserta didik mampu mengidentifikasi dan menerapkan 4 pilar Berpikir Komputasional dalam menyelesaikan masalah sehari-hari." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold d-block mb-2">Pilihan 8 Dimensi Profil Lulusan</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Keimanan dan Ketaqwaan terhadap Tuhan YME" id="p1" checked>
                                        <label class="form-check-label" for="p1">Keimanan & Ketaqwaan</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Kewargaan" id="p2" checked>
                                        <label class="form-check-label" for="p2">Kewargaan</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Penalaran Kritis" id="p3" checked>
                                        <label class="form-check-label" for="p3">Penalaran Kritis</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Kreativitas" id="p4" checked>
                                        <label class="form-check-label" for="p4">Kreativitas</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Kolaborasi" id="p5" checked>
                                        <label class="form-check-label" for="p5">Kolaborasi</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Kemandirian" id="p6" checked>
                                        <label class="form-check-label" for="p6">Kemandirian</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Kesehatan" id="p7" checked>
                                        <label class="form-check-label" for="p7">Kesehatan</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check form-check-inline small">
                                        <input class="form-check-input" type="checkbox" name="profil_lulusan[]" value="Komunikasi" id="p8" checked>
                                        <label class="form-check-label" for="p8">Komunikasi</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="btnGenerate" class="btn btn-primary w-100 py-2.5 rounded-3 shadow-sm fw-bold">
                            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate Modul Ajar AI
                        </button>
                    </form>
                </div>

                <!-- TAB 2: FORM GENERATE SOAL -->
                <div class="tab-pane fade" id="content-soal" role="tabpanel">
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check fs-5"></i>
                        <span class="fw-bold">Form Parameter Naskah Soal AI</span>
                    </div>

                    <form id="formGenerateSoal">
                        @csrf
                        <input type="hidden" name="api_key" value="{{ env('GEMINI_API_KEY', '') }}">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Kelas Target</label>
                                <select name="kelas" class="form-select">
                                    <option value="7">Kelas 7 SMP</option>
                                    <option value="8">Kelas 8 SMP</option>
                                    <option value="9">Kelas 9 SMP</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Jumlah Soal <span class="text-danger">*</span></label>
                                <select name="jumlah_soal" class="form-select" required>
                                    <option value="5">5 Butir Soal</option>
                                    <option value="10" selected>10 Butir Soal</option>
                                    <option value="15">15 Butir Soal</option>
                                    <option value="20">20 Butir Soal</option>
                                    <option value="25">25 Butir Soal</option>
                                    <option value="30">30 Butir Soal</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Bentuk / Jenis Soal <span class="text-danger">*</span></label>
                                <select name="jenis_soal" class="form-select" required>
                                    <option value="Pilihan Ganda (PG) 4 Opsi (A, B, C, D)" selected>Pilihan Ganda (PG)</option>
                                    <option value="Esai / Uraian Pemahaman">Esai / Uraian</option>
                                    <option value="Isian Singkat">Isian Singkat</option>
                                    <option value="Campuran (Pilihan Ganda & Esai)">Campuran (PG & Esai)</option>
                                    <option value="Sumatif Akhir Semester (SAS)">Sumatif Akhir Semester</option>
                                    <option value="Asesmen Formatif / Latihan">Asesmen Formatif</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Level Kognitif Target</label>
                                <select name="level_kognitif" class="form-select">
                                    <option value="Variatif Kognitif (C1 - C4)" selected>Variatif (C1 - C4)</option>
                                    <option value="C1 Mengingat & C2 Memahami">C1 - C2 (Dasar)</option>
                                    <option value="C3 Menerapkan & C4 Menganalisis">C3 - C4 (HOTS / Penerapan)</option>
                                    <option value="High Order Thinking Skills (HOTS)">High Order (HOTS C4-C6)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Materi Ajar / Topik Pembelajaran <span class="text-danger">*</span></label>
                            <textarea name="materi_ajar" class="form-control" rows="3" placeholder="Contoh: Berpikir Komputasional (Dekomposisi, Pengenalan Pola, Abstraksi, Algoritma) atau Pemrograman Visual Scratch." required></textarea>
                        </div>

                        <button type="submit" id="btnGenerateSoal" class="btn btn-success w-100 py-2.5 rounded-3 shadow-sm fw-bold">
                            <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate Naskah Soal AI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- HASIL GENERATE & PREVIEW SIAP CETAK -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 bg-white shadow-sm h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-file-invoice text-success me-2"></i> Preview Hasil AI</h5>
                <div class="d-flex gap-2">
                    <button id="btnDownloadDocx" class="btn btn-outline-primary btn-sm rounded-pill d-none" onclick="downloadDocx()"><i class="fa-solid fa-file-word me-1"></i> Download Word (.doc)</button>
                    <button id="btnPrint" class="btn btn-outline-dark btn-sm rounded-pill d-none" onclick="window.print()"><i class="fa-solid fa-print me-1"></i> Cetak / Save PDF</button>
                </div>
            </div>

            <!-- LOADING SPINNER -->
            <div id="aiLoading" class="text-center my-auto py-5 d-none">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                <h6 class="fw-bold text-dark mb-1" id="loadingTitle">Sedang Memproses dengan Gemini AI...</h6>
                <p class="text-muted small mb-0" id="loadingSub">Menyusun konten pembelajaran terstruktur Kurikulum Merdeka...</p>
            </div>

            <!-- INITIAL EMPTY STATE -->
            <div id="aiEmptyState" class="text-center my-auto py-5 text-muted">
                <i class="fa-solid fa-robot fs-1 text-primary-subtle mb-3"></i>
                <h6>Belum Ada Konten Diproses</h6>
                <p class="small mb-0">Isi parameter di sebelah kiri lalu klik tombol <strong>Generate AI</strong>.</p>
            </div>

            <!-- RESULT CONTENT CONTAINER -->
            <div id="aiResultContainer" class="p-3 bg-white rounded-3 overflow-auto border d-none" style="max-height: 700px;">
                <div id="aiResultHtml"></div>
            </div>
        </div>
    </div>
</div>

@push("scripts")
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const formModul = document.getElementById("formGenerateAi");
        const formSoal = document.getElementById("formGenerateSoal");
        
        const btnGenerate = document.getElementById("btnGenerate");
        const btnGenerateSoal = document.getElementById("btnGenerateSoal");
        
        const loading = document.getElementById("aiLoading");
        const loadingTitle = document.getElementById("loadingTitle");
        const loadingSub = document.getElementById("loadingSub");
        
        const emptyState = document.getElementById("aiEmptyState");
        const resultContainer = document.getElementById("aiResultContainer");
        const resultHtml = document.getElementById("aiResultHtml");
        const btnPrint = document.getElementById("btnPrint");
        const btnDownloadDocx = document.getElementById("btnDownloadDocx");

        window.downloadDocx = function () {
            const contentHtml = resultHtml.innerHTML;
            if (!contentHtml) {
                alert("Belum ada dokumen yang dapat diunduh.");
                return;
            }

            const header = "<html xmlns:v='urn:schemas-microsoft-com:vml' " +
                "xmlns:o='urn:schemas-microsoft-com:office:office' " +
                "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                "xmlns:m='http://schemas.microsoft.com/office/2004/12/omml' " +
                "xmlns='http://www.w3.org/TR/REC-html40'>" +
                "<head><meta charset='utf-8'><title>Hasil AI MGMP Informatika</title>" +
                "<!--[if gte mso 9]><xml>" +
                "<w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom><w:DoNotOptimizeForBrowser/></w:WordDocument>" +
                "</xml><![endif]-->" +
                "<style>" +
                "@page WordSection1 { size: 595.3pt 841.9pt; margin: 72.0pt 72.0pt 72.0pt 72.0pt; mso-header-margin: 36.0pt; mso-footer-margin: 36.0pt; mso-paper-source: 0; }" +
                "div.WordSection1 { page: WordSection1; }" +
                "body { font-family: 'Calibri', 'Segoe UI', Arial, sans-serif; font-size: 11pt; line-height: 1.5; color: #222; margin: 20px; }" +
                "h1, h2, h3, h4 { color: #1a5f7a; font-family: 'Calibri', sans-serif; font-weight: bold; }" +
                "table { border-collapse: collapse; width: 100%; margin: 12px 0; }" +
                "td, th { border: 1px solid #b0bec5; padding: 8px 10px; font-size: 10pt; vertical-align: top; }" +
                "tr:nth-child(even) { background-color: #f8f9fa; }" +
                "ul, ol { margin-left: 20px; padding-left: 0; }" +
                ".badge { background-color: #e3f2fd; color: #0d47a1; padding: 3px 8px; border-radius: 4px; font-size: 9pt; }" +
                "<\/style><\/head><body><div class='WordSection1'>";

            const footer = "<\/div><\/body><\/html>";

            const fullHtml = header + contentHtml + footer;

            const blob = new Blob(['\ufeff' + fullHtml], {
                type: 'application/msword;charset=utf-8'
            });

            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "Dokumen_AI_Informatika_" + new Date().toISOString().slice(0, 10) + ".doc";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        };

        // SUBMIT FORM MODUL AJAR
        formModul.addEventListener("submit", function (e) {
            e.preventDefault();
            processAiRequest("{{ route('generator.generate', [], false) }}", new FormData(formModul), btnGenerate, "Sedang Menyusun Modul Ajar...", "Mengintegrasikan pendekatan Deep Learning (Mindful, Meaningful, & Joyful Learning)...");
        });

        // SUBMIT FORM GENERATE SOAL
        formSoal.addEventListener("submit", function (e) {
            e.preventDefault();
            processAiRequest("{{ route('generator.generateSoal', [], false) }}", new FormData(formSoal), btnGenerateSoal, "Sedang Membuat Naskah Soal AI...", "Menyusun butir soal beserta kunci jawaban & pembahasan...");
        });

        function processAiRequest(url, formData, btnElement, titleText, subText) {
            loadingTitle.innerText = titleText;
            loadingSub.innerText = subText;

            loading.classList.remove("d-none");
            emptyState.classList.add("d-none");
            resultContainer.classList.add("d-none");
            btnPrint.classList.add("d-none");
            btnDownloadDocx.classList.add("d-none");
            if(btnElement) btnElement.disabled = true;

            fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(async res => {
                const contentType = res.headers.get("content-type");
                let data;
                if (contentType && contentType.includes("application/json")) {
                    data = await res.json();
                } else {
                    const text = await res.text();
                    data = { status: "error", message: text ? "Server Error: Response HTML diterima." : `HTTP ${res.status} ${res.statusText}` };
                }

                if (!res.ok) {
                    throw new Error(data.message || `Terjadi kesalahan pada server (Status ${res.status}).`);
                }
                return data;
            })
            .then(data => {
                loading.classList.add("d-none");
                if(btnElement) btnElement.disabled = false;

                if (data.status === "success") {
                    resultHtml.innerHTML = data.html;
                    resultContainer.classList.remove("d-none");
                    btnPrint.classList.remove("d-none");
                    btnDownloadDocx.classList.remove("d-none");
                } else {
                    alert("Error: " + (data.message || "Gagal memproses AI."));
                    emptyState.classList.remove("d-none");
                }
            })
            .catch(err => {
                loading.classList.add("d-none");
                if(btnElement) btnElement.disabled = false;
                alert("Terjadi kesalahan: " + (err.message || err));
                emptyState.classList.remove("d-none");
            });
        }
    });
</script>
@endpush
@endsection