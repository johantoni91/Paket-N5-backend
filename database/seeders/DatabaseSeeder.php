<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                'integrasi',
                'pengajuan',
                'monitor.kartu',
                'layout.kartu',
                'smart',
                'perangkat',
                'akses',
                'log',
                'faq',
                'rating',
                'assessment'
            ]),
            'icon'  => json_encode([
                'user', 'book-open', 'laugh', 'workflow', 'pen', 'monitor', 'credit-card', 'fingerprint', 'radio-receiver', 'key-square', 'history', 'help-circle', 'star', 'file-pen'
            ]),
            'title' => json_encode([
                'Pengguna', 'Satuan Kerja', 'Data Pegawai', 'Integrasi Data Kepegawaian', 'Verifikasi', 'Monitoring', 'Pengaturan Layout Kartu', 'Smart Card Unique Personal Identity', 'Management Perangkat', 'Hak Akses Aplikasi', 'Log Aktivitas', 'FAQ', 'Ulasan', 'Assessment'
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
                'Pengguna', 'Satuan Kerja', 'Data Pegawai', 'Verifikasi', 'Monitoring', 'Pengaturan Layout Kartu', 'Management Perangkat', 'Log Aktivitas', 'FAQ', 'Ulasan'
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
                'Monitoring', 'FAQ'
            ])
        ]);
    }
}
