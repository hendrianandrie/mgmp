<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('judul')->nullable()->after('id');
            $table->string('file_path')->nullable()->after('judul');
            $table->string('jenis_soal', 50)->default('Sumatif')->after('kelas');
            $table->integer('jumlah_download')->default(0)->after('file_path');
            $table->string('ukuran_file', 30)->nullable()->after('jumlah_download');
            $table->text('deskripsi')->nullable()->after('ukuran_file');

            $table->string('materi_pokok')->nullable()->change();
            $table->text('soal_text')->nullable()->change();
            $table->text('opsi_a')->nullable()->change();
            $table->text('opsi_b')->nullable()->change();
            $table->text('opsi_c')->nullable()->change();
            $table->text('opsi_d')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['judul', 'file_path', 'jenis_soal', 'jumlah_download', 'ukuran_file', 'deskripsi']);
        });
    }
};
