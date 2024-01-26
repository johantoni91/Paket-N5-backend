<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\User;
use App\Validation\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public function show()
    {
        try {
            $user = User::orderBy('name', 'desc')->get();
            if ($user) {
                return Endpoint::success(200, 'Berhasil mendapatkan semua users!', $user);
            }
            return Endpoint::success(200, 'Data user kosong!');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Ada error', $th->getMessage());
        }
    }

    public function find($id)
    {
        try {
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
            $data = [
                'name'      => $req->name,
                'email'     => $req->email,
                'phone'     => $req->phone,
                'photo'     => $req->file('photo')->getClientOriginalName() ?? $get_user->photo,
                'password'  => $req->password
            ];
            $this->validate($req, Validate::account());

            //Jika ada gambar yang diupload
            if ($req->hasFile('photo')) {
                $req->file('photo')->move('images', $data['photo']);
            }

            $get_user->update($data);
            return Endpoint::success('Berhasil mengubah user!', User::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $user = User::find($id);
            File::delete('images/' . $user->photo);
            $user->delete();
            return Endpoint::success(200, 'Berhasil menghapus user!');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus user!', $th->getMessage());
        }
    }
}
