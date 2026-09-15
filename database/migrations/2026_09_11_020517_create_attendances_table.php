<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->timestamp('waktu_presensi')->useCurrent();
            $table->enum('metode', ['QR_CODE', 'MANUAL', 'ONLINE'])->default('QR_CODE');
            $table->enum('keterangan', ['HADIR', 'IZIN', 'SAKIT'])->default('HADIR');
            $table->timestamps();
            $table->unique(['activity_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
