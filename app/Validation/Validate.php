<?php

namespace App\Validation;


class Validate
{
    public static function userValidation(): array
    {
        return [
            'name'      => 'required',
            'email'     => 'required|email|email:rfc,dns',
            'phone'     => 'required|numeric',
            'photo'     => 'image|max:2048',
            'password'  => 'required',
        ];
    }

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
