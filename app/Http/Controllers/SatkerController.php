<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Satker;

class SatkerController extends Controller
{
    function index()
    {
        $satker = Satker::all();
        return Endpoint::response('mendapatkan data satker', $satker);
    }
}
