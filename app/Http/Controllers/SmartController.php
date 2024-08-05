<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Pegawai;
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
            $arr = [];
            $pegawai = Pegawai::where('nama', 'LIKE', '%' . $req->nama . '%')->get();
            for ($var = 0; $var < count($pegawai); $var++) {
                $arr[$var] = $pegawai[$var]['nip'];
            }
            $data = Pengajuan::orderBy('created_at', 'asc')
                ->where('nip', 'LIKE', '%' . $req->nip . '%')
                ->whereIn('nip', $arr)
                ->where('kartu', 'LIKE', '%' . $req->kartu . '%')
                ->where('updated_at', 'LIKE', '%' . $req->waktu . '%')
                ->paginate($req->pagination)->appends([
                    'nip'        => $req->nip,
                    'nama'       => $req->nama,
                    'kartu'      => $req->kartu,
                    'waktu'      => $req->waktu,
                    'pagination' => $req->pagination
                ]);
            if (!$data) {
                return Endpoint::success('Tidak ada data kartu');
            }
            return Endpoint::success('Berhasil mendapatkan data kartu', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data kartu!');
        }
    }
}
