<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function getLog()
    {
        try {
            $logs = Log::orderBy('created_at', 'desc')->get();
            return Endpoint::success(200, 'Berhasil mendapatkan data log aktivitas', $logs);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data log aktivitas', $th->getMessage());
        }
    }

    public function search(Request $req)
    {
        try {
            $data = Log::orderBy('created_at', 'desc')->where($req->category, 'LIKE', '%' . $req->search . '%')->get();
            if (!$data) {
                return Endpoint::success(200, 'Tidak ada log aktivitas');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data log berdasarkan kolom ' . $req->category . '. Pencarian ' . $req->value, $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan log aktivitas!', $th->getMessage());
        }
    }
}
