<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\Auth;
use App\Models\Login;
use App\Models\Token;
use App\Models\User;
use App\Validation\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registration(Request $req)
    {
        $file = null;
        if ($req->hasFile('photo')) {
            $file = $req->file('photo')->getClientOriginalName();
            $req->file('photo')->move('images', $file);
        }

        $data = [
            'id'        => mt_rand(),
            'username'  => $req->username,
            'email'     => $req->email,
            'password'  => Hash::make($req->password),
            'phone'     => $req->phone,
            'photo'     => $file,
        ];
        $this->validate($req, Validate::account());

        try {
            User::insert($data);
            return Endpoint::success(200, 'Berhasil registrasi', User::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal registrasi', $th->getMessage());
        }
    }

    public function login(Request $req)
    {
        $data_user  = [
            'username'  => $req->username,
            // 'email'     => $req->email,
            'password'  => $req->password,
        ];
        $remember = $req->remember_me;
        $data_login = [
            'id'               => mt_rand(),
            'username'         => $req->username,
            'password'         => $req->password,
            'ip_address'       => $req->ip_address,
            'browser'          => $req->browser,
            'browser_version'  => $req->browser_version,
            'os'               => $req->os,
            'mobile'           => $req->mobile ?? 1
        ];
        $this->validate($req, Validate::account());

        $check = User::where('username', $data_user['username'])->first();
        // $check = User::where('email', $data_user['email'])->first();

        if (!$check) {
            return Endpoint::failed(401, "User tidak ditemukan, silahkan registrasi terlebih dahulu!");
        }
        if (!Hash::check($data_user['password'], $check->password)) {
            return Endpoint::failed(403, 'Gagal login');
        }
        return Auth::login($remember, $data_login, $check, $data_user);
    }
}
