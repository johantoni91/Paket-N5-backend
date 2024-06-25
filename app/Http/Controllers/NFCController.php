<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class NFCController extends Controller
{
    function retrieveAndUpdate(Request $req)
    {
        try {
            Pengajuan::where('id', $req->id)->update(['uid_kartu' => $req->uid]);
            return Endpoint::success('UID: ' . $req->uid . ' Berhasil disimpan!');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }
}
