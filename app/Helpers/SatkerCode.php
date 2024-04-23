<?php

namespace App\Helpers;

use App\Models\Notif;

class SatkerCode
{
    public static function parent($var): String
    {
        if ($var == "00") {
            return '0';
        } elseif (preg_match('/^\d{2}$/', $var) && $var != "00") {
            return '1';
        } elseif (preg_match('/^\d{4}$/', $var)) {
            return '2';
        }
    }

    public static function notification($nama, $code, $satker)
    {
        switch ($code) {
            case '0':
                Notif::insert([
                    'notifikasi'  => $nama . ' mengajukan kartu.',
                    'kode_satker' => $satker,
                    'satker'      => '0',
                ]);
                break;
            case '1':
                Notif::insert([
                    'notifikasi'  => $nama . ' mengajukan kartu.',
                    'kode_satker' => $satker,
                    'satker'      => '1',
                ]);
                break;
            default:
                Notif::insert([
                    'notifikasi'  => $nama . ' mengajukan kartu.',
                    'kode_satker' => $satker,
                    'satker'      => '2',
                ]);
                break;
        }
    }
}
