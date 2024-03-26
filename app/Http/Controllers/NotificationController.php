<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Notif;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function index()
    {
        try {
            return Endpoint::success(200, 'Berhasil', Notif::get());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function store(Request $req)
    {
        try {
            Notif::insert([
                'notifikasi'    => $req->notifikasi
            ]);
            return Endpoint::success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function destroy($id)
    {
        try {
            Notif::where('id', $id)->delete();
            return Endpoint::success(200, 'Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }
}
