<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->string('nip', 30)->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('gelar_depan', 20)->nullable();
            $table->string('nama_lengkap');
            $table->string('gelar_belakang', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'GTT', 'Honorer'])->default('PNS');
            $table->string('mapel_diampu')->default('Informatika');
            $table->text('alamat_rumah')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
