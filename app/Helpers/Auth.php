<?php

namespace App\Helpers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\Token;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class Auth
{
    public static function login($remember, $data_login, $check)
    {
        try {
            if ($remember == "1" && !$check->remember_me) {
                $check->update([
                    'remember_token' => Hash::make(mt_rand())
                ]);
            }

            Log::insert($data_login);
            $token = Token::where('users_id', $check->id)->first();
            if (!$token) {
                Token::insert([
                    'users_id'          => $check->id,
                    'token'             => Crypt::encrypt(mt_rand())
                ]);
            }

            $data = [
                'user'              => $check,
                'token'             => Token::where('users_id', $check->id)->first()
            ];

            return Endpoint::success(200, 'Berhasil login', $data);
        } catch (\Throwable $th) {
            return Endpoint::success(400, 'Gagal Login', $th->getMessage());
        }
    }
}
