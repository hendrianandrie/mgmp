<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('kelas', ['7', '8', '9'])->default('7');
            $table->string('materi_pokok');
            $table->text('indikator_soal')->nullable();
            $table->enum('level_kognitif', ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'])->default('C2');
            $table->text('soal_text');
            $table->string('soal_gambar')->nullable();
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->enum('kunci_jawaban', ['A', 'B', 'C', 'D'])->default('A');
            $table->text('pembahasan')->nullable();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
