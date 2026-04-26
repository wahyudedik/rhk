<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rhk_id' => ['required', 'exists:rhks,id'],
            'jenis_rhk_id' => ['required', 'exists:jenis_rhks,id'],
            'bulan' => ['required', 'string', 'max:20'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2099'],
            'latar_belakang' => ['nullable', 'string'],
            'maksud_tujuan' => ['nullable', 'string'],
            'ruang_lingkup' => ['nullable', 'string'],
            'dasar' => ['nullable', 'string'],
            'kegiatan_dilaksanakan' => ['nullable', 'string'],
            'hasil_dicapai' => ['nullable', 'string'],
            'simpulan' => ['nullable', 'string'],
            'saran' => ['nullable', 'string'],
            'penutup' => ['nullable', 'string'],
            'file_dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
            // Header
            'header_instansi_1' => ['nullable', 'string', 'max:255'],
            'header_instansi_2' => ['nullable', 'string', 'max:255'],
            'header_instansi_3' => ['nullable', 'string', 'max:255'],
            'header_instansi_4' => ['nullable', 'string', 'max:255'],
            // TTD
            'ttd_kota' => ['nullable', 'string', 'max:100'],
            'ttd_tanggal' => ['nullable', 'date'],
            'ttd_jabatan' => ['nullable', 'string', 'max:255'],
            'ttd_nama' => ['nullable', 'string', 'max:255'],
            'ttd_nip' => ['nullable', 'string', 'max:30'],
            'ttd_gambar' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'ttd_gambar_canvas' => ['nullable', 'string'], // base64 dari canvas
            // Dokumentasi
            'gps_photo_id' => ['nullable', 'exists:gps_photos,id'],
            'foto_dokumentasi.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'keterangan_dokumentasi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'rhk_id' => 'RHK',
            'jenis_rhk_id' => 'Jenis RHK',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
        ];
    }
}
