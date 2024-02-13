<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;

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
}
