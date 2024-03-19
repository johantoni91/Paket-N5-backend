<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kartu;
use Illuminate\Http\Request;

class KartuController extends Controller
{
    function index()
    {
        try {
            $kartu = Kartu::paginate(5);
            if (!$kartu) {
                return Endpoint::warning(200, 'Kartu masih kosong');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data kartu', $kartu);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan kartu', $th->getMessage());
        }
    }

    function store(Request $req)
    {
        try {
            $input = [
                'id'        => mt_rand(),
                'kartu'     => $req->kartu,
                'layout'    => $req->layout
            ];
            Kartu::insert($input);
            return Endpoint::success(200, 'Berhasil menambahkan kartu');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan kartu', $th->getMessage());
        }
    }
}
