<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            'role'      => 'superadmin',
            'access'    => json_encode([
                'users', 'satker', 'pegawai', 'pengajuan', 'kartu', 'hak_akses', 'log_aktivitas', 'faq', 'ulasan'
            ])
        ]);

        Menu::insert([
            'role'      => 'admin',
            'access'    => json_encode([
                'users', 'satker', 'pegawai', 'pengajuan', 'kartu', 'log_aktivitas', 'faq', 'ulasan'
            ])
        ]);

        Menu::insert([
            'role'      => 'pegawai',
            'access'    => json_encode([
                'kartu', 'faq', 'ulasan'
            ])
        ]);
    }
}
