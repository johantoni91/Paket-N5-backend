<?php

namespace App\Helpers;

// NB : SATKER_KODE || KODE 
// 0 = KEJAGUNG
// 1 = KEJATI
// 2 = KEJARI
// 3 = CABJARI

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
        } elseif (preg_match('/^\d{6}$/', $var)) {
            return '3';
        }
    }
}
