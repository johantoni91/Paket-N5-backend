<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Menu;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index()
    {
        try {
            $roles = Menu::get();
            if (!$roles) {
                return Endpoint::warning('Data roles masih kosong');
            }
            return Endpoint::success('Berhasil mendapatkan data roles', $roles);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data roles');
        }
    }

    function find(Request $req)
    {
        return response(Menu::where('role', $req->role)->first(), 200);
    }

    function findId(Request $req)
    {
        return Endpoint::success('Berhasil', Menu::find($req->id));
    }

    function store(Request $req)
    {
        try {
            $input = [
                'id'        => mt_rand(1, 4),
                'role'      => $req->role
            ];
            Menu::insert($input);
            return Endpoint::success('Berhasil menambahkan role');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menambahkan data role');
        }
    }

    function update(Request $req, $id)
    {
        try {
            Menu::where('id', $id)->update(['route'    => $req->route, 'icon'    => $req->icon, 'title'    => $req->title]);
            return Endpoint::success('Berhasil mengubah data Role');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah data role');
        }
    }

    function destroy($id)
    {
        try {
            Menu::where('id', $id)->delete();
            return Endpoint::success('Berhasil menghapus role');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menghapus Role');
        }
    }
}
