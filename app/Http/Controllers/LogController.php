<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Login;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function getLog()
    {
        try {
            $log = Login::all();
            return Endpoint::success(true, 'Berhasil mendapatkan data log aktivitas', $log);
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Gagal mendapatkan data log aktivitas', $th->getMessage());
        }
    }

    function destroy($id)
    {
        try {
            Login::find($id)->delete();
            return Endpoint::success(true, "Data log dengan " . $id);
        } catch (\Throwable $th) {
            return Endpoint::failed(false, "Gagal menghapus data log", $th->getMessage());
        }
    }
}
