<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\Satker;
use Illuminate\Http\Request;

class SatkerController extends Controller
{
    private $satker = '(satker)';
    function index()
    {
        try {
            $satker = Satker::all();
            return Endpoint::success(200, 'mendapatkan data satker', $satker);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, "gagal mendapatkan data satker", $th->getMessage());
        }
    }

    function store(Request $req)
    {
        try {
            $code = mt_rand();
            $akronim = "";
            if ($req->type == 0) {
                $akronim = "KJ";
            } elseif ($req->type == 1) {
                $akronim = "KT";
            } elseif ($req->type == 1) {
                $akronim = "KN";
            } else {
                $akronim = "CN";
            }
            $data = [
                'satker_name'    => $req->satker,
                'satker_type'    => $req->type,
                'satker_phone'   => $req->phone,
                'satker_email'   => $req->email,
                'satker_address' => $req->address,
                'satker_akronim' => $akronim . $code,
                'satker_code'    => $code
            ];
            $this->validate($req, [
                'satker'  => 'required',
                'phone'   => 'required',
                'email'   => 'required|email:rfc,dns',
                'address' => 'required',
            ]);

            $log = [
                'id'                => mt_rand(),
                'users_id'          => $req->users_id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->satker . ' Tambah data satker.',
            ];
            Log::insert($log);
            Satker::insert($data);
            return Endpoint::success(200, 'Berhasil menambahkan satker', Satker::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan satker', $th->getMessage());
        }
    }
}
