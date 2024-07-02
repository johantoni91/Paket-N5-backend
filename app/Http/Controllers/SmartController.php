<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class SmartController extends Controller
{
    function index($satker)
    {
        try {
            $smart_card = Pengajuan::orderBy('updated_at')->where('kode_satker', $satker)->where('uid_kartu', 'NOT LIKE', '')->paginate(5);
            return Endpoint::success('Berhasil', $smart_card);
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function search(Request $req)
    {
        try {
            $smart = Pengajuan::orderBy('updated_at');
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }
}
