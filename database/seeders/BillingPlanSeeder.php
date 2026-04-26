<?php

namespace Database\Seeders;

use App\Models\BillingPlan;
use Illuminate\Database\Seeder;

class BillingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'nama' => 'Trial',
                'slug' => 'trial',
                'harga' => 0,
                'durasi_hari' => 30,
                'batas_laporan_per_bulan' => 5,
                'batas_gps_photo_per_bulan' => 10,
                'fitur' => [
                    'Akses selama 30 hari',
                    'Maksimal 5 laporan per bulan',
                    'Maksimal 10 foto GPS per bulan',
                    'Semua jenis RHK tersedia',
                    'Upload dokumen pendukung',
                ],
                'is_trial' => true,
                'is_active' => true,
                'urutan' => 0,
            ],
            [
                'nama' => 'Starter',
                'slug' => 'starter',
                'harga' => 29000,
                'durasi_hari' => 30,
                'batas_laporan_per_bulan' => 20,
                'batas_gps_photo_per_bulan' => 20,
                'fitur' => [
                    'Akses penuh selama 30 hari',
                    'Maksimal 20 laporan per bulan',
                    'Maksimal 20 foto GPS per bulan',
                    'Semua 9 RHK & 17 jenis kegiatan',
                    'Upload dokumen pendukung',
                    'Riwayat laporan lengkap',
                ],
                'is_trial' => false,
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'Professional',
                'slug' => 'professional',
                'harga' => 59000,
                'durasi_hari' => 30,
                'batas_laporan_per_bulan' => 50,
                'batas_gps_photo_per_bulan' => 50,
                'fitur' => [
                    'Akses penuh selama 30 hari',
                    'Maksimal 50 laporan per bulan',
                    'Maksimal 50 foto GPS per bulan',
                    'Semua 9 RHK & 17 jenis kegiatan',
                    'Upload dokumen pendukung',
                    'Riwayat laporan lengkap',
                    'Prioritas dukungan via WhatsApp',
                ],
                'is_trial' => false,
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'Enterprise',
                'slug' => 'enterprise',
                'harga' => 99000,
                'durasi_hari' => 30,
                'batas_laporan_per_bulan' => null, // unlimited
                'batas_gps_photo_per_bulan' => null, // unlimited
                'fitur' => [
                    'Akses penuh selama 30 hari',
                    'Laporan tidak terbatas',
                    'Foto GPS tidak terbatas',
                    'Semua 9 RHK & 17 jenis kegiatan',
                    'Upload dokumen pendukung',
                    'Riwayat laporan lengkap',
                    'Prioritas dukungan via WhatsApp',
                    'Konsultasi pengisian laporan',
                ],
                'is_trial' => false,
                'is_active' => true,
                'urutan' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            BillingPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
