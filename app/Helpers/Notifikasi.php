<?php

namespace App\Helpers;

use App\Models\Notif;

class Notifikasi
{
    public static function add($pesan, $nip, $satker) // Boolean
    {
        return Notif::insert([
            'notifikasi'    => $pesan,
            'nip'           => $nip,
            'satker'        => $satker
        ]);
    }
}
