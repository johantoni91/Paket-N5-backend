<?php

namespace App\Helpers;

use App\Api\Endpoint;
use App\Models\Kewenangan;
use App\Models\Log;
use App\Models\Token;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class Auth
{
    public static function login($remember, $data_login, $check)
    {
        // try {
        //     if ($remember == "1" && !$check->remember_me) {
        //         $check->update([
        //             'remember_token' => Hash::make(mt_rand())
        //         ]);
        //     }

        //     Log::insert($data_login);
        //     $token = Token::where('users_id', $check->id)->first();
        //     if (!$token) {
        //         Token::insert([
        //             'users_id'  => $check->id,
        //             'logs_id'   => Log::where('users_id', $data_login['users_id'])
        //                 ->where('ip_address', $data_login['ip_address'])
        //                 ->where('browser', $data_login['browser'])
        //                 ->where('mobile', $data_login['mobile'])
        //                 ->first()['id'],
        //             'token'     => Crypt::encrypt(mt_rand())
        //         ]);
        //     }

        //     $user = Kewenangan::with(['users'])->where('users_id', $check['id'])->first();
        //     if ($user->status == '1') {
        //         $data = [
        //             'user'              => $user,
        //             'token'             => Token::where('users_id', $check->id)->first()
        //         ];
        //         return Endpoint::success(200, 'Berhasil login', $data);
        //     }
        //     return Endpoint::warning(200, 'Tanyakan pada superadmin');
        // } catch (\Throwable $th) {
        //     return Endpoint::success(400, 'Gagal Login', $th->getMessage());
        // }
    }
}
