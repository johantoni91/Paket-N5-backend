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
            return Endpoint::success(true, 'mendapatkan data satker', $satker);
        } catch (\Throwable $th) {
            return Endpoint::failed(false, "gagal mendapatkan data satker", $th->getMessage());
        }
    }
}
