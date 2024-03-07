<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index()
    {
        try {
            $roles = Role::all();
            if (!$roles) {
                return Endpoint::warning(200, 'Data roles masih kosong');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data roles', $roles);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data roles', $th->getMessage());
        }
    }

    function store(Request $req)
    {
        try {
            $input = [
                'id'        => mt_rand(1, 4),
                'role'      => $req->role,
                'access'    => $req->access
            ];
            Role::insert($input);
            return Endpoint::success(200, 'Berhasil menambahkan role');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data role', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $input = [
                'role'      => $req->role,
                'access'    => $req->access
            ];
            Role::where('id', $id)->update($input);
            return Endpoint::success(200, 'Berhasil mengubah data Role');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah data role', $th->getMessage());
        }
    }

    function destroy($id)
    {
        try {
            Role::where('id', $id)->delete();
            return Endpoint::success(200, 'Berhasil menghapus role');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus Role', $th->getMessage());
        }
    }
}
