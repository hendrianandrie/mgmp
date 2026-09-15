<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiGeneratorController extends Controller
{
    public function index()
    {
        $categories = Category::where('tipe', 'MATERI')->get();

        return view('generator.index', compact('categories'));
    }

    public function generate(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');

        $request->validate([
            'kelas' => 'required|in:7,8,9',
            'tujuan_pembelajaran' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'profil_lulusan' => 'required|array',
        ]);

        $apiKey = $request->input('api_key') ?: env('GEMINI_API_KEY', '');

        if (! $apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sistem Gemini API Key belum dikonfigurasi di file .env server. Silakan hubungi administrator.',
            ], 400);
        }

        $kelas = $request->kelas;
        $tp = $request->tujuan_pembelajaran;
        $ta = $request->tahun_ajaran;
        $profil = implode(', ', $request->profil_lulusan);

        $prompt = 'Buka peran Anda sebagai pakar pengembang Kurikulum Merdeka & Spesialis Pembelajaran Informatika SMP.
Buatkan Modul Ajar Informatika secara sangat lengkap, terstruktur, profesional, dan siap cetak dengan ketentuan sebagai berikut:

IDENTITAS MODUL:
- Mata Pelajaran: Informatika
- Kelas: '.$kelas.' SMP
- Tahun Ajaran: '.$ta.'
- Alokasi Waktu: 2 x 40 Menit (1 Pertemuan)
- Dimensi Profil Lulusan / Profil Pelajar Pancasila: '.$profil.'
- Tujuan Pembelajaran (TP): '.$tp.'
- Pendekatan Pedagogi: Deep Learning (Mindful, Meaningful, & Joyful Learning)

PETUNJUK STRUKTUR OUTPUT:
Sajikan modul ajar dalam elemen HTML rapi (tanpa blok ```html) lengkap dengan styling CSS inline yang elegan mencakup:
1. IDENTITAS & INFORMASI UMUM (Tabel Rapi)
2. ELEMEN & CAPAIAN PEMBELAJARAN
3. DIMENSI PROFIL LULUSAN & PROFIL PELAJAR
4. MEDIA, ALAT, & SUMBER BELAJAR INFORMATIKA
5. DESAIN PENGALAMAN BELAJAR DEEP LEARNING:
   - Pendahuluan (Mindful Learning - Kesiapan Mental, Kesadaran Diri, Apersepsi): 15 Menit
   - Kegiatan Inti (Meaningful Learning - Eksplorasi Konsep Informatika, Diskusi Kelompok, Hands-On Activity): 50 Menit
   - Penutup & Refleksi (Joyful Learning - Perayaan Hasil Belajar, Refleksi Bermakna, Umpan Balik Positif): 15 Menit
6. ASESMEN PEMBELAJARAN (Formatif, Sumatif, & Rubrik Penilaian Kriteria Ketuntasan)
7. LEMBAR KERJA PESERTA DIDIK (LKPD) RINGKAS

Gunakan bahasa Indonesia yang baku, inspiratif, dan padat makna.';

        $models = ['gemini-3.6-flash', 'gemini-3.5-flash'];
        $lastErrorMessage = 'Gagal terhubung ke Gemini API.';

        foreach ($models as $model) {
            $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$apiKey;

            try {
                $response = Http::timeout(120)->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $generatedText = '';
                    if (isset($json['candidates'][0]['content']['parts'])) {
                        foreach ($json['candidates'][0]['content']['parts'] as $part) {
                            if (isset($part['text'])) {
                                $generatedText .= $part['text'];
                            }
                        }
                    }

                    if (! empty($generatedText)) {
                        $cleanHtml = preg_replace("/^```html\s*/i", '', $generatedText);
                        $cleanHtml = preg_replace("/^```\s*/i", '', $cleanHtml);
                        $cleanHtml = preg_replace('/```$/', '', $cleanHtml);

                        return response()->json([
                            'status' => 'success',
                            'html' => $cleanHtml,
                            'model_used' => $model,
                        ]);
                    }
                } else {
                    $errJson = $response->json();
                    $lastErrorMessage = $errJson['error']['message'] ?? ('Gagal terhubung ke model '.$model.' (HTTP '.$response->status().')');
                }
            } catch (\Exception $e) {
                $lastErrorMessage = 'Error: '.$e->getMessage();
            }
        }

        return response()->json(['status' => 'error', 'message' => $lastErrorMessage], 400);
    }

    public function generateSoal(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time', '300');

        $request->validate([
            'kelas' => 'required|in:7,8,9',
            'materi_ajar' => 'required|string',
            'jenis_soal' => 'required|string',
            'jumlah_soal' => 'required|integer|min:1|max:50',
            'level_kognitif' => 'nullable|string',
        ]);

        $apiKey = $request->input('api_key') ?: env('GEMINI_API_KEY', '');

        if (! $apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sistem Gemini API Key belum dikonfigurasi di file .env server. Silakan hubungi administrator.',
            ], 400);
        }

        $kelas = $request->kelas;
        $materi = $request->materi_ajar;
        $jenis = $request->jenis_soal;
        $jumlah = $request->jumlah_soal;
        $level = $request->level_kognitif ?? 'Variatif (C1 - C4)';

        $prompt = "Buka peran Anda sebagai pakar pembuat Naskah Soal Asesmen Informatika SMP Kurikulum Merdeka.
Buatkan naskah soal asesmen Informatika secara sangat lengkap, terstruktur, profesional, dan siap cetak dengan spesifikasi sebagai berikut:

SPESIFIKASI SOAL:
- Mata Pelajaran: Informatika
- Kelas: {$kelas} SMP
- Topik / Materi Ajar: {$materi}
- Jenis Asesmen / Bentuk Soal: {$jenis}
- Jumlah Soal: {$jumlah} Butir Soal
- Level Kognitif Target: {$level}

PETUNJUK STRUKTUR OUTPUT:
Sajikan naskah soal dalam elemen HTML rapi (tanpa menggunakan penutup markdown ```html) lengkap dengan styling CSS inline yang elegan mencakup:
1. KOP NASKAH SOAL MGMP INFORMATIKA (Judul Asesmen, Kelas, Alokasi Waktu, Petunjuk Umum)
2. DAFTAR BUTIR SOAL TERSTRUKTUR:
   - Jika Pilihan Ganda: Sajikan pertanyaan dengan opsi A, B, C, D yang jelas.
   - Jika Esai / Isian: Sajikan pertanyaan berbasis pemahaman, studi kasus, atau penalaran komputasional.
3. KUNCI JAWABAN & PEDOMAN PENSKORAN / PEMBAHASAN LENGKAP di bagian paling bawah naskah.

Gunakan bahasa Indonesia yang baku, presisi, inspiratif, dan sesuai dengan tingkat pemikiran siswa SMP.";

        $models = ['gemini-3.6-flash', 'gemini-3.5-flash'];
        $lastErrorMessage = 'Gagal terhubung ke Gemini API.';

        foreach ($models as $model) {
            $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$apiKey;

            try {
                $response = Http::timeout(120)->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $generatedText = '';
                    if (isset($json['candidates'][0]['content']['parts'])) {
                        foreach ($json['candidates'][0]['content']['parts'] as $part) {
                            if (isset($part['text'])) {
                                $generatedText .= $part['text'];
                            }
                        }
                    }

                    if (! empty($generatedText)) {
                        $cleanHtml = preg_replace("/^```html\s*/i", '', $generatedText);
                        $cleanHtml = preg_replace("/^```\s*/i", '', $cleanHtml);
                        $cleanHtml = preg_replace('/```$/', '', $cleanHtml);

                        return response()->json([
                            'status' => 'success',
                            'html' => $cleanHtml,
                            'model_used' => $model,
                        ]);
                    }
                } else {
                    $errJson = $response->json();
                    $lastErrorMessage = $errJson['error']['message'] ?? ('Gagal terhubung ke model '.$model.' (HTTP '.$response->status().')');
                }
            } catch (\Exception $e) {
                $lastErrorMessage = 'Error: '.$e->getMessage();
            }
        }

        return response()->json(['status' => 'error', 'message' => $lastErrorMessage], 400);
    }
}
