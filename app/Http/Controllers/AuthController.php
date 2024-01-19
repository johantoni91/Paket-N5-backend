<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Login;
use App\Validation\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registration(Request $req)
    {
        $data = [
            'username'              => $req->username,
            'password'              => Hash::make($req->password),
            'ip_address'            => $req->ip_address,
            'browser'               => $req->browser,
            'browser_version'       => $req->browser_version,
            'os'                    => $req->os,
            'registration_token'    => Str::random(15),
            'mobile'                => $req->mobile ?? 0,
        ];
        $this->validate($req, Validate::account());

        $registrasi = Login::insert($data);
        if (!$registrasi) {
            return Endpoint::failed(400, 'Gagal registrasi');
        }
        return Endpoint::success(200, 'Berhasil registrasi', Login::latest()->first());
    }

    public function login(Request $req)
    {
        $data = [
            'username'              => $req->username,
            'password'              => $req->password,
            'ip_address'            => $req->ip_address,
            'browser'               => $req->browser,
            'browser_version'       => $req->browser_version,
            'os'                    => $req->os,
            'mobile'                => $req->mobile ?? 0
        ];
        $this->validate($req, Validate::account());

        $check = Login::where('username', $data['username'])->first();
        if (!Hash::check($data['password'], $check->password)) {
            return Endpoint::failed(400, 'Gagal login');
        }

        return Endpoint::success(200, 'Berhasil login', $check);
    }
}
