<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rhk_id')->constrained('rhks')->cascadeOnDelete();
            $table->foreignId('jenis_rhk_id')->constrained('jenis_rhks')->cascadeOnDelete();
            $table->string('bulan'); // e.g. "Februari"
            $table->integer('tahun');
            // Bagian laporan
            $table->text('latar_belakang')->nullable();
            $table->text('maksud_tujuan')->nullable();
            $table->text('ruang_lingkup')->nullable();
            $table->text('dasar')->nullable();
            $table->text('kegiatan_dilaksanakan')->nullable();
            $table->text('hasil_dicapai')->nullable();
            $table->text('simpulan')->nullable();
            $table->text('saran')->nullable();
            $table->text('penutup')->nullable();
            // Dokumen pendukung
            $table->string('file_dokumen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
