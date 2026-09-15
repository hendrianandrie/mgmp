<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Material;
use App\Models\Member;
use App\Models\Position;
use App\Models\Question;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users & Roles
        $adminUser = User::create([
            'name' => 'Super Administrator',
            'username' => 'admin',
            'email' => 'admin@mgmp-informatika-ciamis.or.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $pengurusUser = User::create([
            'name' => 'Drs. H. Ahmad Fauzi, M.Pd.',
            'username' => 'pengurus',
            'email' => 'ahmad.fauzi@mgmp-informatika-ciamis.or.id',
            'password' => Hash::make('password'),
            'role' => 'pengurus',
            'phone' => '082198765432',
            'is_active' => true,
        ]);

        $guru1 = User::create([
            'name' => 'Yuni Kartika, S.Kom.',
            'username' => 'yuni',
            'email' => 'yuni@mgmp-informatika-ciamis.or.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'phone' => '085712345678',
            'is_active' => true,
        ]);

        $guru2 = User::create([
            'name' => 'Hendrian Andri, S.Pd.',
            'username' => 'hendrian',
            'email' => 'hendrian@mgmp-informatika-ciamis.or.id',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'phone' => '081987654321',
            'is_active' => true,
        ]);

        // 2. Schools in Ciamis
        $s1 = School::create([
            'npsn' => '20211501',
            'nama_sekolah' => 'SMP Negeri 5 Ciamis',
            'status' => 'NEGERI',
            'kecamatan' => 'Ciamis',
            'alamat' => 'Jl. Jend. Sudirman No. 120 Ciamis',
            'nama_kepsek' => 'Drs. H. Maman Suherman, M.Pd.',
            'no_telp_sekolah' => '0265-771234',
        ]);

        $s2 = School::create([
            'npsn' => '20211502',
            'nama_sekolah' => 'SMP Negeri 1 Ciamis',
            'status' => 'NEGERI',
            'kecamatan' => 'Ciamis',
            'alamat' => 'Jl. Ir. H. Juanda No. 80 Ciamis',
            'nama_kepsek' => 'Dr. Agus Rahmat, M.Pd.',
            'no_telp_sekolah' => '0265-772345',
        ]);

        $s3 = School::create([
            'npsn' => '20211503',
            'nama_sekolah' => 'SMP Negeri 1 Kawali',
            'status' => 'NEGERI',
            'kecamatan' => 'Kawali',
            'alamat' => 'Jl. Veteran No. 15 Kawali',
            'nama_kepsek' => 'H. Endang Kurnia, S.Pd.',
            'no_telp_sekolah' => '0265-791111',
        ]);

        // 3. Members
        $mPengurus = Member::create([
            'user_id' => $pengurusUser->id,
            'school_id' => $s2->id,
            'nip' => '197505121999031002',
            'nuptk' => '4532753655200003',
            'gelar_depan' => 'Drs. H.',
            'nama_lengkap' => 'Ahmad Fauzi',
            'gelar_belakang' => 'M.Pd.',
            'jenis_kelamin' => 'L',
            'status_kepegawaian' => 'PNS',
            'mapel_diampu' => 'Informatika',
            'alamat_rumah' => 'Kec. Ciamis, Kab. Ciamis',
        ]);

        $mYuni = Member::create([
            'user_id' => $guru1->id,
            'school_id' => $s1->id,
            'nip' => '198808202015042001',
            'nuptk' => '8942766667300002',
            'gelar_depan' => '',
            'nama_lengkap' => 'Yuni Kartika',
            'gelar_belakang' => 'S.Kom.',
            'jenis_kelamin' => 'P',
            'status_kepegawaian' => 'PPPK',
            'mapel_diampu' => 'Informatika',
            'alamat_rumah' => 'Kec. Ciamis, Kab. Ciamis',
        ]);

        $mHendrian = Member::create([
            'user_id' => $guru2->id,
            'school_id' => $s1->id,
            'nip' => '199201152020121005',
            'nuptk' => '1234771234100005',
            'gelar_depan' => '',
            'nama_lengkap' => 'Hendrian Andri',
            'gelar_belakang' => 'S.Pd.',
            'jenis_kelamin' => 'L',
            'status_kepegawaian' => 'PNS',
            'mapel_diampu' => 'Informatika',
            'alamat_rumah' => 'Kec. Ciamis, Kab. Ciamis',
        ]);

        // 4. MGMP Structure / Positions
        Position::create(['nama_jabatan' => 'Ketua MGMP', 'periode' => '2024-2027', 'member_id' => $mPengurus->id, 'urutan' => 1]);
        Position::create(['nama_jabatan' => 'Wakil Ketua MGMP', 'periode' => '2024-2027', 'member_id' => $mHendrian->id, 'urutan' => 2]);
        Position::create(['nama_jabatan' => 'Sekretaris MGMP', 'periode' => '2024-2027', 'member_id' => $mYuni->id, 'urutan' => 3]);
        Position::create(['nama_jabatan' => 'Bendahara MGMP', 'periode' => '2024-2027', 'member_id' => null, 'urutan' => 4]);
        Position::create(['nama_jabatan' => 'Sie Pengembangan Kurikulum & Pembelajaran', 'periode' => '2024-2027', 'member_id' => null, 'urutan' => 5]);

        // 5. Categories
        $c1 = Category::create(['nama_kategori' => 'Berpikir Komputasional', 'tipe' => 'MATERI']);
        $c2 = Category::create(['nama_kategori' => 'Algoritma & Pemrograman', 'tipe' => 'MATERI']);
        $c3 = Category::create(['nama_kategori' => 'Sistem Komputer & Jaringan', 'tipe' => 'MATERI']);
        $c4 = Category::create(['nama_kategori' => 'Soal Asesmen Sumatif & PAS', 'tipe' => 'SOAL']);

        // 6. Activity
        Activity::create([
            'kode_kegiatan' => 'ACT-202609-001',
            'nama_kegiatan' => 'Workshop Penyusunan Modul Ajar & Bank Soal Informatika Kurikulum Merdeka',
            'deskripsi' => 'Kegiatan koordinasi dan penyusunan modul ajar Informatika kelas 7, 8, dan 9 semester ganjil.',
            'tanggal_kegiatan' => date('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '15:00:00',
            'lokasi' => 'Aula SMP Negeri 1 Ciamis',
            'narasumber' => 'Dr. Eng. Rahmat Hidayat, M.T.',
            'qr_code_token' => Str::random(32),
            'status' => 'BERJALAN',
        ]);

        // 7. Announcement
        Announcement::create([
            'judul' => 'Undangan Pertemuan Rutin MGMP Informatika SMP Kab. Ciamis',
            'slug' => 'undangan-pertemuan-rutin-mgmp-informatika-2026',
            'isi_pengumuman' => 'Diberitahukan kepada seluruh bapak/ibu guru pengampu mata pelajaran Informatika SMP Negeri & Swasta se-Kabupaten Ciamis untuk hadir pada kegiatan workshop penyusunan Perangkat Pembelajaran.',
            'is_public' => true,
            'author_id' => $pengurusUser->id,
        ]);

        // 8. Materials
        Material::create([
            'judul' => 'Modul Ajar Informatika Kelas 7 - Berpikir Komputasional',
            'deskripsi' => 'Modul ajar lengkap dengan LKPD dan materi presentasi Berpikir Komputasional (BK).',
            'category_id' => $c1->id,
            'kelas' => '7',
            'uploader_id' => $pengurusUser->id,
            'jumlah_download' => 42,
        ]);

        Material::create([
            'judul' => 'Bahan Ajar Algoritma & Pemrograman Scratch Kelas 8',
            'deskripsi' => 'Bahan ajar latihan pengenalan blok pemrograman visual Scratch 3.0.',
            'category_id' => $c2->id,
            'kelas' => '8',
            'uploader_id' => $mYuni->user_id,
            'jumlah_download' => 28,
        ]);

        // 9. Questions
        Question::create([
            'category_id' => $c4->id,
            'kelas' => '7',
            'materi_pokok' => 'Berpikir Komputasional',
            'indikator_soal' => 'Siswa dapat mengidentifikasi 4 pilar Berpikir Komputasional.',
            'level_kognitif' => 'C2',
            'soal_text' => 'Manakah di bawah ini yang BUKAN merupakan empat pilar utama dalam Berpikir Komputasional (Computational Thinking)?',
            'opsi_a' => 'Dekomposisi',
            'opsi_b' => 'Pengenalan Pola',
            'opsi_c' => 'Abstraksi',
            'opsi_d' => 'Kompilasi Otomatis',
            'kunci_jawaban' => 'D',
            'pembahasan' => 'Empat pilar Berpikir Komputasional adalah Dekomposisi, Pengenalan Pola, Abstraksi, dan Algoritma.',
            'creator_id' => $pengurusUser->id,
        ]);
    }
}
