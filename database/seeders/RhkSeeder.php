<?php

namespace Database\Seeders;

use App\Models\JenisRhk;
use App\Models\Rhk;
use Illuminate\Database\Seeder;

class RhkSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Terlaksananya penyaluran bansos kepada Keluarga Penerima Manfaat (KPM) PKH tepat sasaran dan tepat jumlah',
                'urutan' => 1,
                'jenis' => [
                    ['nama' => 'Melakukan edukasi dan sosialisasi pencairan secara tunai dan non tunai', 'urutan' => 1],
                    ['nama' => 'Melaksanakan Supervisi Permasalahan Bantuan Sosial', 'urutan' => 2],
                    ['nama' => 'Melaksanakan Monitoring Pemantauan Penyaluran Bantuan Sosial', 'urutan' => 3],
                    ['nama' => 'Melaksanakan Penelitian penyaluran bantuan Sosial', 'urutan' => 4],
                ],
            ],
            [
                'nama' => 'Terlaksananya pertemuan P2K2 sesuai dengan ketentuan',
                'urutan' => 2,
                'jenis' => [
                    ['nama' => 'Melaksanakan Pertemuan Peningkatan Kemampuan Keluarga (P2K2)', 'urutan' => 1],
                ],
            ],
            [
                'nama' => 'Terlaksananya Verifikasi Komitmen Pendidikan, Kesehatan dan Kesejahteraan Sosial secara akurat sesuai dengan ketentuan',
                'urutan' => 3,
                'jenis' => [
                    ['nama' => 'Melaksanakan Verifikasi Komitmen Pendidikan, Kesehatan dan Kesejahteraan Sosial', 'urutan' => 1],
                    ['nama' => 'Melakukan pendampingan, mediasi, dan fasilitasi kepada KPM PKH dalam proses perubahan perilaku, pola pikir yang mandiri dan produktif', 'urutan' => 2],
                ],
            ],
            [
                'nama' => 'Tersedianya Data KPM graduasi yang disusun sesuai dengan instrumen dan ketentuan',
                'urutan' => 4,
                'jenis' => [
                    ['nama' => 'Melakukan usulan KPM Graduasi mandiri dan Pemberdayaan PPSE', 'urutan' => 1],
                ],
            ],
            [
                'nama' => 'Terlaksananya Verifikasi, Validasi dan Permutakhiran Data KPM secara akurat sesuai dengan ketentuan',
                'urutan' => 5,
                'jenis' => [
                    ['nama' => 'Melaksanakan Pemutakhiran Data', 'urutan' => 1],
                    ['nama' => 'Melaksanakan proses bisnis PKH yang meliputi verifikasi validasi calon penerima bantuan sosial', 'urutan' => 2],
                ],
            ],
            [
                'nama' => 'Terlaksananya kegiatan kasus adaptif (Respon kasus/pengaduan/kebencanaan/kerentanan) disusun secara lengkap dan akurat',
                'urutan' => 6,
                'jenis' => [
                    ['nama' => 'Melaksanakan Respon Kasus/Pengaduan/Kebencanaan/Kerentanan', 'urutan' => 1],
                ],
            ],
            [
                'nama' => 'Tersedianya Data Analisis Laporan Bulanan yang disusun sesuai dengan Ketentuan',
                'urutan' => 7,
                'jenis' => [
                    ['nama' => 'Membuat laporan bulanan pelaksanaan PKH dan laporan lainnya', 'urutan' => 1],
                ],
            ],
            [
                'nama' => 'Terlaksananya direktif pimpinan sesuai dengan penugasan; program Kementrian Sosial',
                'urutan' => 8,
                'jenis' => [
                    ['nama' => 'Melaksanakan Tindak Lanjut Hasil Pemeriksaan (TLHP)', 'urutan' => 1],
                    ['nama' => 'Melakukan sosialisasi kebijakan dan bisnis proses PKH kepada aparat pemerintah daerah atau media sosial secara berkala', 'urutan' => 2],
                    ['nama' => 'Mengikuti Rapat Koordinasi, Sosialisasi Kebijakan Proses Bisnis PKH dan Penguatan Kapasitas SDM', 'urutan' => 3],
                    ['nama' => 'Tugas Lainnya (Penugasan lainnya program Kementrian Sosial)', 'urutan' => 4],
                ],
            ],
            [
                'nama' => 'Terlaksananya Penyebaran Berita Baik Kementrian Sosial',
                'urutan' => 9,
                'jenis' => [
                    ['nama' => 'Berperan aktif dalam memanfaatkan, menggunakan, melibatkan dan menyebarkan Media Sosial untuk menyampaikan semua program di Kementerian Sosial', 'urutan' => 1],
                ],
            ],
        ];

        foreach ($data as $item) {
            $rhk = Rhk::create([
                'nama' => $item['nama'],
                'urutan' => $item['urutan'],
            ]);

            foreach ($item['jenis'] as $jenis) {
                JenisRhk::create([
                    'rhk_id' => $rhk->id,
                    'nama' => $jenis['nama'],
                    'urutan' => $jenis['urutan'],
                ]);
            }
        }
    }
}
