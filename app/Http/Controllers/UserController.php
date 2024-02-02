<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    private $table = '(User)';
    public function show()
    {
        try {
            $user = User::orderBy('name', 'desc')->get();
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
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $req->users_id,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->table . ' Lihat data users' . $req->id,
                'created_at'        => Carbon::now()
            ]);
            $data = User::where('id', $id)->first();
            return Endpoint::success(200, 'Berhasil menemukan user!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }


    public function update(Request $req, $id)
    {
        try {
            $get_user = User::find($id);
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $id,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->table . ' Ubah data user ' . $id,
                'created_at'        => Carbon::now()
            ]);
            $data = [
                'username'  => $req->username,
                'name'      => $req->name,
                'email'     => $req->email,
                'phone'     => $req->phone,
                'photo'     => $req->hasFile('photo') == true ? mt_rand() . '.' . $req->file('photo')->getClientOriginalExtension() : $get_user->photo
            ];
            $this->validate($req, [
                'username'  => 'required'
            ]);

            //Jika ada gambar yang diupload
            if ($req->hasFile('photo')) {
                $req->file('photo')->move('images', $data['photo']);
                File::delete('images/' . $get_user->photo);
            }

            $get_user->update($data);
            return Endpoint::success(200, 'Berhasil mengubah user!', User::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }


    public function delete(Request $req, $id)
    {
        try {
            $user = User::find($id);
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $req->users_id,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->table . ' Hapus data user ' . $req->id,
                'created_at'        => Carbon::now()
            ]);
            File::delete('images/' . $user->photo);
            $user->delete();
            return Endpoint::success(200, 'Berhasil menghapus user!');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus user!', $th->getMessage());
        }
    }
}
