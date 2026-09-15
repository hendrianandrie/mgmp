<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique()->nullable();
            $table->string('nama_sekolah');
            $table->enum('status', ['NEGERI', 'SWASTA'])->default('NEGERI');
            $table->string('kecamatan');
            $table->text('alamat')->nullable();
            $table->string('nama_kepsek')->nullable();
            $table->string('no_telp_sekolah', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
