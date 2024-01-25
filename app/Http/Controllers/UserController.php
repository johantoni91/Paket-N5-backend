<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\User;
use App\Validation\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function show()
    {
        try {
            $user = User::orderBy('name', 'desc')->get();
            return Endpoint::success(true, 'Berhasil mendapatkan semua users!', $user);
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Data users kosong!', $th->getMessage());
        }
    }


    public function store(Request $req)
    {
        try {
            $data = [
                'username'  => $req->username,
                'email'     => $req->email,
                'phone'     => $req->phone,
                'photo'     => $req->file('photo')->getClientOriginalName(),
                'password'  => Hash::make($req->password)
            ];
            $this->validate($req, Validate::account());

            //Jika ada gambar yang diupload
            if ($req->hasFile('photo')) {
                $req->file('photo')->move('images', $data['photo']);
            }

            User::insert($data);
            return Endpoint::success(true, 'Berhasil membuat user!', User::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Gagal membuat user!', $th->getMessage());
        }
    }


    public function find($id)
    {
        try {
            $data = User::where('id', $id)->first();
            return Endpoint::success(true, 'Berhasil menemukan user!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(false, $th->getMessage());
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
            return Endpoint::failed(false, $th->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return Endpoint::success(true, 'Berhasil menghapus user!');
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Gagal menghapus user!', $th->getMessage());
        }
    }
}
