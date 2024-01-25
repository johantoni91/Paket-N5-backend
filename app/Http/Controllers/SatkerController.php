<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Satker;

class SatkerController extends Controller
{
    function index()
    {
        try {
            $satker = Satker::all();
            return Endpoint::success(200, 'mendapatkan data satker', $satker);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, "gagal mendapatkan data satker", $th->getMessage());
        }
    }
}
