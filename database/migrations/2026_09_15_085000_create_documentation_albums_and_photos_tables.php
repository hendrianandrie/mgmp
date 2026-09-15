<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentation_albums', function (Blueprint $table) {
            $table->id();
            $table->string('nama_album');
            $table->foreignId('activity_id')->nullable()->constrained('activities')->nullOnDelete();
            $table->date('tanggal')->nullable();
            $table->string('komisariat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('cover_foto')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('documentation_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('documentation_albums')->cascadeOnDelete();
            $table->string('judul_foto')->nullable();
            $table->text('caption')->nullable();
            $table->string('foto_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentation_photos');
        Schema::dropIfExists('documentation_albums');
    }
};
