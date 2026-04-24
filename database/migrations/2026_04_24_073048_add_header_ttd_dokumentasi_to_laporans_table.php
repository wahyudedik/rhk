<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            // Header instansi
            $table->string('header_instansi_1')->nullable()->after('penutup')->comment('Baris 1 header, misal: KEMENTERIAN SOSIAL RI');
            $table->string('header_instansi_2')->nullable()->after('header_instansi_1');
            $table->string('header_instansi_3')->nullable()->after('header_instansi_2');
            $table->string('header_instansi_4')->nullable()->after('header_instansi_3')->comment('Alamat instansi');

            // Tanda tangan
            $table->string('ttd_kota')->nullable()->after('header_instansi_4')->comment('Dibuat di ...');
            $table->date('ttd_tanggal')->nullable()->after('ttd_kota');
            $table->string('ttd_jabatan')->nullable()->after('ttd_tanggal');
            $table->string('ttd_nama')->nullable()->after('ttd_jabatan');
            $table->string('ttd_nip')->nullable()->after('ttd_nama');
            $table->string('ttd_gambar')->nullable()->after('ttd_nip')->comment('Path file gambar TTD');

            // Dokumentasi foto (JSON array of paths, max 10)
            $table->json('foto_dokumentasi')->nullable()->after('ttd_gambar');
            $table->text('keterangan_dokumentasi')->nullable()->after('foto_dokumentasi')->comment('Keterangan/caption dokumentasi');
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn([
                'header_instansi_1', 'header_instansi_2', 'header_instansi_3', 'header_instansi_4',
                'ttd_kota', 'ttd_tanggal', 'ttd_jabatan', 'ttd_nama', 'ttd_nip', 'ttd_gambar',
                'foto_dokumentasi', 'keterangan_dokumentasi',
            ]);
        });
    }
};
