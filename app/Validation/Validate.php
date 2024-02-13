<?php

namespace App\Validation;


class Validate
{
    public static function user(): array
    {
        return [
            'username'  => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
            'photo'     => 'images|mimes:jpg,png,jpeg,gif'
        ];
    }

    public static function account($data): array
    {
        return [
            'username'  => 'required',
            'name'      => 'required',
            'password'  => 'required'
        ];
    }

    public static function login(): array
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
