<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Log;
use App\Models\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user = '(User)';
    public function show($satker)
    {
        try {
            $satker_code = SatkerCode::parent($satker);
            if ($satker_code == '0') {
                $user = User::orderBy('name')->paginate(10);
            } else {
                $user = User::orderBy('name')->where('satker', 'LIKE', $satker . '%')->paginate(10);
            }
            return Endpoint::success('Berhasil mendapatkan semua users!', $user);
        } catch (\Throwable $th) {
            return Endpoint::failed('Ada error');
        }
    }

    function findById($id)
    {
        $user = User::find($id);
        return Endpoint::success('Berhasil', $user);
    }

    function store(Request $req)
    {
        try {
            $pegawai = Pegawai::where('nip', $req->nip)->where('nrp', $req->nrp)->first();
            if (!$pegawai) {
                return Endpoint::warning('Tidak terdaftar dalam pegawai');
            }
            $input = [
                'nip'       => $req->nip,
                'nrp'       => $req->nrp,
                'username'  => $req->username,
                'name'      => $req->name,
                'roles'     => $req->role,
                'satker'    => $req->satker,
                'email'     => $req->email,
                'phone'     => $req->phone,
                'password'  => Hash::make($req->password)
            ];
            if ($req->role == 'pegawai') {
                $input['username'] = $req->nip;
                $input['name'] = $req->nip;
                $input['satker'] = $pegawai->nama_satker;
                $input['photo'] = $pegawai->foto_pegawai;
            }
            if (User::where('username', $req->username)->where('nip', $req->nip)->where('nrp', $req->nrp)->first()) {
                return Endpoint::warning('User sudah terdaftar');
            }

            if ($req->hasFile('photo')) {
                $file = $req->nip . '_profile' . '.' . $req->file('photo')->getClientOriginalExtension();
                $req->file('photo')->move('images', $file);
                $input['photo'] = $file;
            }
            User::insert($input);
            return Endpoint::success(' Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    public function find(Request $req, $id)
    {
        try {
            $data = User::where('id', $id)->orWhere('nip', $id)->orWhere('nrp', $id)->first();
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
            return Endpoint::success('Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('User tidak ditemukan');
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $data_user = User::find($id);
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
                'roles'     => $req->roles ?? $data_user->roles,
                'satker'    => $req->satker,
                'email'     => $req->email,
                'phone'     => $req->phone,
                'password'  => $req->password == null ? $data_user->password : Hash::make($req->password),
                'photo'     => $req->hasFile('photo') == true ? mt_rand() . '.' . $req->file('photo')->getClientOriginalExtension() : $data_user->photo
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
            return Endpoint::success('Berhasil mengubah user!', $data_user);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah user', $th->getMessage());
        }
    }

    public function status($id, $stat)
    {
        try {
            $status = User::find($id);
            if (!$status) {
                return Endpoint::failed('User tidak ditemukan');
            }
            $status->status = $stat == "1" ? '0' : '1';
            $status->save();
            return Endpoint::success('Berhasil mengubah status');
        } catch (\Throwable $th) {
            return Endpoint::failed('Terjadi kesalahan!');
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            // Log::insert([
            //     'id'                => mt_rand(),
            //     'users_id'          => $id,
            //     'username'          => $user->username,
            //     'ip_address'        => $req->ip_address,
            //     'browser'           => $req->browser,
            //     'browser_version'   => $req->browser_version,
            //     'os'                => $req->os,
            //     'mobile'            => $req->mobile,
            //     'log_detail'        => $this->user . ' Hapus data user ' . $req->username,
            //     'created_at'        => Carbon::now()
            // ]);
            File::delete('images/' . $user->photo);
            $user->delete();
            return Endpoint::success('Berhasil menghapus user!');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menghapus user!');
        }
    }

    function search(Request $req)
    {
        try {
            $data = User::where('roles', 'LIKE', '%' . $req->role . '%')
                ->where('status', 'LIKE', '%' . $req->status . '%')
                ->where('nip', 'LIKE', '%' . $req->nip . '%')
                ->where('nrp', 'LIKE', '%' . $req->nrp . '%')
                ->where('username', 'LIKE', '%' . $req->username . '%')
                ->where('name', 'LIKE', '%' . $req->name . '%')
                ->where('email', 'LIKE', '%' . $req->email . '%')
                ->where('phone', 'LIKE', '%' . $req->phone . '%')
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
                return Endpoint::success('Pengguna tidak ada');
            }
            return Endpoint::success('Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan Pengguna!');
        }
    }
}
