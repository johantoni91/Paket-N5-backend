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
            return Endpoint::success(200, 'Berhasil mendapatkan data log aktivitas', Login::get());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data log aktivitas', $th->getMessage());
        }
    }
}
