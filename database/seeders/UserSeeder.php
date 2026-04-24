<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@laporan-asn.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => Role::Superadmin,
                'email_verified_at' => now(),
            ]
        );

        // Contoh akun pelanggan dengan data lengkap
        User::updateOrCreate(
            ['email' => 'fadhilah@laporan-asn.test'],
            [
                'name' => 'Fadhilah Hamzah',
                'nip' => '198906132025211066',
                'jabatan' => 'Penata Layanan Operasional',
                'desa' => 'Jetis',
                'kecamatan' => 'Jetis',
                'kabupaten' => 'Mojokerto',
                'provinsi' => 'Jawa Timur',
                'password' => Hash::make('password'),
                'role' => Role::Pelanggan,
                'email_verified_at' => now(),
            ]
        );

        // Pelanggan demo tanpa data lengkap
        User::updateOrCreate(
            ['email' => 'pelanggan@laporan-asn.test'],
            [
                'name' => 'Pelanggan Demo',
                'password' => Hash::make('password'),
                'role' => Role::Pelanggan,
                'email_verified_at' => now(),
            ]
        );
    }
}
