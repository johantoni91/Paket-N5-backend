<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kewenangan;
use App\Models\Log;
use App\Models\Satker;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user = '(User)';
    public function show()
    {
        try {
            $user = Kewenangan::with(['users'])->orderBy('created_at', 'asc')->paginate(10);
            if (!$user) {
                return Endpoint::success(200, 'Data user kosong!');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan semua users!', $user);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Ada error', $th->getMessage());
        }
    }

    public function find(Request $req, $id)
    {
        try {
            $data = Kewenangan::with(['users'])->where('id', $id)->orWhere('users_id', $id)->first();
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->user . ' Lihat data users ' . $req->username,
                'created_at'        => Carbon::now()
            ]);
            return Endpoint::success(200, 'Berhasil menemukan user!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $get_user = Kewenangan::with(['users'])->where('users_id', $id)->first();
            $data_user = User::where('id', $id)->first();
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->user . ' Ubah data user ' . $req->username,
                'created_at'        => Carbon::now()
            ]);
            $user = [
                'nip'       => $req->nip,
                'nrp'       => $req->nrp,
                'username'  => $req->username,
                'name'      => $req->name,
                'email'     => $req->email,
                'phone'     => $req->phone,
                'password'  => $req->password == null ? $data_user->password : Hash::make($req->password),
                'photo'     => $req->hasFile('photo') == true ? mt_rand() . '.' . $req->file('photo')->getClientOriginalExtension() : $get_user->users->photo
            ];

            $this->validate($req, [
                'username'  => 'required',
                'name'      => 'required',
                'email'     => 'required',
            ]);

            //Jika ada gambar yang diupload
            if ($req->hasFile('photo')) {
                File::delete('images/' . $data_user->photo);
                $req->file('photo')->move('images', $user['photo']);
            }
            $data_user->update($user);
            if ($req->roles) {
                $get_user->update(['roles' => $req->roles]);
            }
            if ($req->satker) {
                $get_user->update(['satker' => $req->satker]);
            }

            $data = [
                'user'              => Kewenangan::with(['users'])->where('users_id', $id)->first(),
                'token'             => Token::where('users_id', $id)->first()
            ];
            return Endpoint::success(200, 'Berhasil mengubah user!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah user', $th->getMessage());
        }
    }

    public function status($id, $stat)
    {
        try {
            $status = Kewenangan::where('users_id', $id)->first();
            if (!$status) {
                return Endpoint::failed(400, 'User tidak ditemukan');
            }
            $status->status = $stat == "1" ? '0' : '1';
            $status->save();
            return Endpoint::success(200, 'Berhasil mengubah status');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Terjadi kesalahan!', $th->getMessage());
        }
    }

    public function delete(Request $req, $id)
    {
        try {
            $kewenangan = Kewenangan::where('users_id', $id)->first();
            $user = User::where('id', $id)->first();
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $id,
                'username'          => $user->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->user . ' Hapus data user ' . $req->username,
                'created_at'        => Carbon::now()
            ]);
            File::delete('images/' . $user->photo);
            Token::where('users_id', $id)->delete();
            $kewenangan->delete();
            $user->delete();
            return Endpoint::success(200, 'Berhasil menghapus user!');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus user!', $th->getMessage());
        }
    }

    function search(Request $req)
    {
        try {
            $data = Kewenangan::with('users')->where('roles', 'LIKE', '%' . $req->role . '%')
                ->where('status', 'LIKE', '%' . $req->status . '%')
                ->whereHas('users', function ($query) use ($req) {
                    $query->where('nip', 'LIKE', '%' . $req->nip . '%')
                        ->where('nrp', 'LIKE', '%' . $req->nrp . '%')
                        ->where('username', 'LIKE', '%' . $req->username . '%')
                        ->where('name', 'LIKE', '%' . $req->name . '%')
                        ->where('email', 'LIKE', '%' . $req->email . '%')
                        ->where('phone', 'LIKE', '%' . $req->phone . '%');
                })
                ->paginate(10)->appends([
                    'nip'      => $req->nip,
                    'nrp'      => $req->nrp,
                    'username' => $req->username,
                    'name'     => $req->name,
                    'email'    => $req->email,
                    'phone'    => $req->phone,
                    'roles'    => $req->roles,
                    'status'   => $req->status
                ]);
            if (!$data) {
                return Endpoint::success(200, 'Pengguna tidak ada');
            }
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan Pengguna!', $th->getMessage());
        }
    }
}
