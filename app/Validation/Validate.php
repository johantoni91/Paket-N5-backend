<?php

namespace App\Validation;


class Validate
{
    public static function account(): array
    {
        return [
            'username'  => 'required',
            'password'  => 'required'
        ];
    }

    public static function pegawai(): array
    {
        return [
            'draw'  => 'required',
            'start' => 'required',
            'length' => 'required',
        ];
    }
}
