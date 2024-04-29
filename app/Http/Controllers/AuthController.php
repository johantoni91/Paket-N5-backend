<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\Pegawai;
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
        $user = User::where('nip', $req->nip)->orWhere('nrp', $req->nrp)->orWhere('username', $req->username)->first();
        if ($user) {
            return Endpoint::warning(400, 'Pengguna sudah terdaftar');
        }
        if ($req->hasFile('photo')) {
            $file = $req->nip . '_profile' . '.' . $req->file('photo')->getClientOriginalExtension();
            $req->file('photo')->move('images', $file);
        }

        $data = [
            'id'            => mt_rand(),
            'nip'           => $req->nip,
            'nrp'           => $req->nrp,
            'username'      => $req->username,
            'name'          => $req->name,
            'email'         => $req->email,
            'roles'         => $req->role,
            'satker'        => $req->satker,
            'password'      => Hash::make($req->password),
            'phone'         => $req->phone,
            'photo'         => $file,
            'created_at'    => Carbon::now()
        ];
        $this->validate($req, Validate::account($data));

        try {
            if (!Satker::where('satker_code', $req->satker)->first()) {
                return Endpoint::failed(400, 'Gagal registrasi', 'Kode satker tidak terdaftar');
            }
            User::insert($data);
            return Endpoint::success(200, 'Berhasil registrasi', User::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal registrasi', $th->getMessage());
        }
    }

    public function login(Request $req)
    {
        try {
            $input  = [
                'username'   => $req->username,
                'password'   => $req->password
            ];
            $this->validate($req, Validate::login());
            $user = User::where('username', $input['username'])->first();
            if (!$user) {
                return Endpoint::failed(400, "User tidak ditemukan, silahkan registrasi terlebih dahulu!");
            }
            if ($user->status == '0') {
                return Endpoint::warning(400, "Akun anda sudah tidak aktif!");
            }

            $check_pegawai = Pegawai::where('nip', $req->username)->orWhere('nrp', $req->username)->first();
            if ($check_pegawai) {
                $input['username'] = $check_pegawai->nama;
            }

            if (!Hash::check($input['password'], $user->password)) {
                return Endpoint::failed(400, 'Gagal login');
            }

            $data_login = [
                'id'                => mt_rand(),
                'users_id'          => $user->id,
                'username'          => $input['username'],
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->table . ' Login',
                'created_at'        => Carbon::now()
            ];

            if (!$user->token) {
                $user->update(['token' => encrypt(mt_rand())]);
            }
            Log::insert($data_login);
            return Endpoint::success(200, 'Berhasil login', $user);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Username / Password Salah!');
        }
    }
}
