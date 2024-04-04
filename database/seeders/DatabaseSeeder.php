<?php

namespace Database\Seeders;

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
                'satker',
                'pegawai',
                'pengajuan',
                'layout.kartu',
                'log',
                'faq',
                'rating'
            ]),
            'icon'  => json_encode([
                'book-open', 'laugh', 'pen', 'credit-card', 'key-square', 'history', 'help-circle', 'star'
            ]),
            'title' => json_encode([
                'Satuan Kerja', 'Pegawai', 'Pengajuan', 'Kartu', 'Hak Akses', 'Log Aktivitas', 'FAQ', 'Ulasan'
            ])
        ]);

        Menu::insert([
            'id'    => mt_rand(),
            'role'  => 'pegawai',
            'route' => json_encode([
                'kartu',
                'faq',
                'rating'
            ]),
            'icon'  => json_encode([
                'credit-card', 'help-circle', 'star'
            ]),
            'title' => json_encode([
                'Kartu', 'FAQ', 'Ulasan'
            ])
        ]);

        Satker::insert([
            'id'             => mt_rand(),
            'satker_name'    => 'KEJAKSAAN TINGGI KARAWANG',
            'satker_type'    => 1,
            'satker_phone'   => '089671022623',
            'satker_email'   => 'kejati_krw@gmail.com',
            'satker_address' => 'Pinayungan, Karawang, 40321',
            'satker_akronim' => 'KT' . mt_rand(),
            'satker_code'    => mt_rand()
        ]);
    }
}
