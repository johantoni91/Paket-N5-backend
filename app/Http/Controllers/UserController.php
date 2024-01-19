<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Token;
use App\Models\User;
use App\Validation\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // public function index()
    // {
    //     $user = User::select('id', 'name', 'email', 'email_verified_at', 'photo', 'phone', 'password')->orderBy('name', 'asc')->get();
    //     if ($user == null) {
    //         return Endpoint::response(300, 'Data users null!');
    //     }
    //     return Endpoint::response(200, 'Success get all users!', $user);
    // }


    // public function store(Request $req)
    // {
    //     $validation = Validate::userValidation();
    //     $data = [
    //         'name'      => $req->name,
    //         'email'     => $req->email,
    //         'phone'     => $req->phone,
    //         'photo'     => $req->file('photo') ?? null,
    //         'password'  => Hash::make($req->password)
    //     ];
    //     $this->validate($req, $validation); // Melakukan validasi

    //     //Jika ada gambar yang diupload
    //     if ($req->hasFile('photo')) {
    //         $req->file('photo')->move('images', $data['photo']);
    //     }

    //     //Aksi membuat user & respon
    //     $user = User::create($data);
    //     if (!$user) {
    //         return Endpoint::response(400, 'Failed create user!');
    //     }
    //     return Endpoint::response(200, 'Success create user!', User::latest()->first());
    // }


    // public function show($id)
    // {
    //     $data = User::where('id', $id)->first();
    //     if (!$data) {
    //         return Endpoint::response(400, 'Missing parameter!');
    //     }
    //     return Endpoint::response(200, 'Found user!', $data);
    // }


    // public function update(Request $req, $id)
    // {
    //     $validation = Validate::userValidation();
    //     $get_user = User::find($id);
    //     $data = [
    //         'name'      => $req->name,
    //         'email'     => $req->email,
    //         'phone'     => $req->phone,
    //         'photo'     => $req->file('photo') ?? $get_user->photo,
    //         'password'  => $req->password
    //     ];
    //     $this->validate($req, $validation); // Melakukan validasi

    //     //Jika ada gambar yang diupload
    //     if ($req->hasFile('photo')) {
    //         $req->file('photo')->move('images', $data['photo']);
    //     }

    //     if (!$get_user) {
    //         return response()->json(['message' => 'user not found!'], 400);
    //     }

    //     // Aksi & Respon
    //     $user = User::where('id', $id)->update($data);
    //     return Endpoint::response('mengubah user', User::where('id', $id)->first());
    // }


    // public function destroy($id)
    // {
    //     $get_user = User::find($id);
    //     if (!$get_user) {
    //         return response()->json(['message'  => 'User not found!'], 400);
    //     }

    //     // Aksi hapus user & Respon
    //     $del = $get_user->delete();
    //     return Endpoint::response('menghapus user', $del);
    // }


    // function login(Request $req)
    // {
    //     $validation = Validate::loginValidation();
    //     $this->validate($req, $validation); // Melakukan validasi

    //     $user = User::where('email', $req->email)->first();
    //     //Aksi Login & Respon
    //     if (!Hash::check($req->password, $user->password) && $req->email == $user->email) {
    //         return Endpoint::responseWithData(400, 'Login failed!');
    //     }

    //     Token::insert([
    //         'token'      => Hash::make($user['id'] . time()),
    //         'users_id'   => $user->id
    //     ]);
    //     return Endpoint::responseWithData(200, 'Login success!', $user, Token::latest()->first());
    // }


    // function logout($id)
    // {
    //     $user = Token::where('users_id', $id)->delete();

    //     return Endpoint::response('logout user', $user);
    // }
}
