<?php

namespace Database\Seeders;

use App\Models\Kewenangan;
use App\Models\Menu;
use App\Models\Satker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Menu::insert([
            'id'    => mt_rand(),
            'role'  => 'superadmin',
            'route' => json_encode([
                'user',
                'satker',
                'pegawai',
                'pengajuan',
                'monitor.kartu',
                'layout.kartu',
                'perangkat',
                'akses',
                'log',
                'faq',
                'rating'
            ]),
            'icon'  => json_encode([
                'user', 'book-open', 'laugh', 'pen', 'monitor', 'credit-card', 'radio-receiver', 'key-square', 'history', 'help-circle', 'star'
            ]),
            'title' => json_encode([
                'Pengguna', 'Satuan Kerja', 'Pegawai', 'Pengajuan', 'Monitor Kartu', 'Layout Kartu', 'Perangkat', 'Hak Akses', 'Log Aktivitas', 'FAQ', 'Ulasan'
            ])
        ]);

        Menu::insert([
            'id'    => mt_rand(),
            'role'  => 'admin',
            'route' => json_encode([
                'user',
                'satker',
                'pegawai',
                'pengajuan',
                'monitor.kartu',
                'layout.kartu',
                'perangkat',
                'log',
                'faq',
                'rating'
            ]),
            'icon'  => json_encode([
                'user', 'book-open', 'laugh', 'pen', 'monitor', 'credit-card', 'radio-receiver', 'history', 'help-circle', 'star'
            ]),
            'title' => json_encode([
                'Pengguna', 'Satuan Kerja', 'Pegawai', 'Pengajuan', 'Monitor Kartu', 'Layout Kartu', 'Perangkat', 'Log Aktivitas', 'FAQ', 'Ulasan'
            ])
        ]);

        Menu::insert([
            'id'    => mt_rand(),
            'role'  => 'pegawai',
            'route' => json_encode([
                'monitor.kartu',
                'faq'
            ]),
            'icon'  => json_encode([
                'monitor', 'help-circle'
            ]),
            'title' => json_encode([
                'Monitor Kartu', 'FAQ'
            ])
        ]);
    }
}
