<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\Auth;
use App\Models\Kewenangan;
use App\Models\Log;
use App\Models\Satker;
use App\Models\User;
use App\Validation\Validate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $table = '(Login)';
    public function registration(Request $req)
    {
        $file = null;
        if ($req->hasFile('photo')) {
            $file = mt_rand() . '.' . $req->file('photo')->getClientOriginalExtension();
            $req->file('photo')->move('images', $file);
        }

        $data = [
            'id'            => mt_rand(),
            'username'      => $req->username,
            'name'          => $req->name,
            'nip'           => $req->nip,
            'nrp'           => $req->nrp,
            'email'         => $req->email,
            'password'      => Hash::make($req->password),
            'phone'         => $req->phone,
            'photo'         => $file,
            'created_at'    => Carbon::now()
        ];
        $this->validate($req, Validate::account($data));

        try {
            User::insert($data);
            Kewenangan::insert([
                'id'            => mt_rand(),
                'users_id'      => $data['id'],
                'roles'         => $req->roles,
                'status'        => $req->status ?? 1
            ]);
            return Endpoint::success(200, 'Berhasil registrasi', User::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal registrasi', $th->getMessage());
        }
    }

    public function login(Request $req)
    {
        $data_user  = [
            'username'   => $req->username,
            'password'   => $req->password
        ];
        $remember = $req->remember_me;
        $this->validate($req, Validate::login());

        $check = User::where('username', $data_user['username'])->first();

        if (!$check) {
            return Endpoint::failed(400, "User tidak ditemukan, silahkan registrasi terlebih dahulu!");
        }
        if (!Hash::check($data_user['password'], $check->password)) {
            return Endpoint::failed(400, 'Gagal login');
        }
        $data_login = [
            'id'                => mt_rand(),
            'users_id'          => $check->id,
            'username'          => $data_user['username'],
            'ip_address'        => $req->ip_address,
            'browser'           => $req->browser,
            'browser_version'   => $req->browser_version,
            'os'                => $req->os,
            'mobile'            => $req->mobile,
            'log_detail'        => $this->table . ' Login',
            'created_at'        => Carbon::now()
        ];

        return Auth::login($remember, $data_login, $check, $data_user);
    }
}
