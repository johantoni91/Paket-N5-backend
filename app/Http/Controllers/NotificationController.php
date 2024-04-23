<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Notif;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function index($id)
    {
        try {
            $code = SatkerCode::parent($id);
            $notif = Notif::where('satker', $code)->where('kode_satker', 'LIKE', $id . '%')->get();
            if (!$notif) {
                return Endpoint::warning(200, '');
            }
            return Endpoint::success(200, 'Ada yang mengajukan kartu', $notif);
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
