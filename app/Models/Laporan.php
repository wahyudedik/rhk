<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'gps_photo_id',
        'rhk_id',
        'jenis_rhk_id',
        'bulan',
        'tahun',
        'latar_belakang',
        'maksud_tujuan',
        'ruang_lingkup',
        'dasar',
        'kegiatan_dilaksanakan',
        'hasil_dicapai',
        'simpulan',
        'saran',
        'penutup',
        'file_dokumen',
        // Header instansi
        'header_instansi_1',
        'header_instansi_2',
        'header_instansi_3',
        'header_instansi_4',
        // Tanda tangan
        'ttd_kota',
        'ttd_tanggal',
        'ttd_jabatan',
        'ttd_nama',
        'ttd_nip',
        'ttd_gambar',
        // Dokumentasi
        'foto_dokumentasi',
        'keterangan_dokumentasi',
        // File generated
        'file_pdf',
        'file_docx',
        // Template
        'is_template',
        'template_name',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'ttd_tanggal' => 'date',
            'foto_dokumentasi' => 'array',
            'is_template' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rhk(): BelongsTo
    {
        return $this->belongsTo(Rhk::class);
    }

    public function jenisRhk(): BelongsTo
    {
        return $this->belongsTo(JenisRhk::class);
    }

    public function gpsPhoto(): BelongsTo
    {
        return $this->belongsTo(GpsPhoto::class);
    }

    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    public function scopeLaporans($query)
    {
        return $query->where('is_template', false);
    }
}
