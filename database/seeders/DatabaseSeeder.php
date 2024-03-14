<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            'username'         => 'yan',
            'name'             => 'yan',
            'email'            => 'yan@gmail.com',
            'phone'            => '089671022623',
            'password'         => '123',
            'ip_address'       => '192.168.4.116',
            'browser'          => 'Google Chrome',
            'browser_version'  => '113.0.5672.92',
            'os'               => 'MacOS',
            'mobile'           => '0',
            'nip'              => '1810631170189123',
            'roles'            => 'superadmin',
        ]);

        Menu::insert([
            'id'    => mt_rand(),
            'role'  => 'superadmin',
            'route' => json_encode([
                'user',
                'satker',
                'pegawai',
                'pengajuan',
                'kartu',
                'akses',
                'log',
                'faq',
                'rating'
            ]),
            'icon'  => json_encode([
                'user', 'book-open', 'laugh', 'pen', 'credit-card', 'key-square', 'history', 'help-circle', 'star'
            ]),
            'title' => json_encode([
                'Manajemen Pengguna', 'Satuan Kerja', 'Pegawai', 'Pengajuan', 'Kartu', 'Hak Akses', 'Log Aktivitas', 'FAQ', 'Ulasan'
            ])
        ]);

        Menu::insert([
            'id'    => mt_rand(),
            'role'  => 'admin',
            'route' => json_encode([
                'satker',
                'pegawai',
                'pengajuan',
                'kartu',
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
    }
}
