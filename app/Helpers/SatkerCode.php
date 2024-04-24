<?php

namespace App\Helpers;

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
