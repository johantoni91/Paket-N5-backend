<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    function index($id)
    {
        try {
            $kode = SatkerCode::parent($id);
            if ($kode == '0') {
                $data = Pengajuan::orderBy('created_at', 'asc')->paginate(5);
            } else {
                $data = Pengajuan::orderBy('created_at', 'asc')->where('kode_satker', 'LIKE', $id . '%')->paginate(5);
            }
            return Endpoint::success('Berhasil monitoring data pengajuan', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal monitoring data pengajuan');
        }
    }

    function search(Request $req)
    {
        try {
            $pegawai = Pegawai::where('nama', 'LIKE', '%' . $req->nama . '%')->get();
            for ($var = 0; $var < count($pegawai); $var++) {
                $arr[$var] = $pegawai[$var]['nip'];
            }
            $data = Pengajuan::orderBy('created_at')
                ->where('status', $req->status)
                ->where('alasan', $req->alasan)
                ->whereIn('nip', $arr)
                ->paginate($req->pagination)->appends([
                    'nip'        => $arr,
                    'status'     => $req->status,
                    'alasan'     => $req->alasan,
                    'pagination' => $req->pagination
                ]);
            return Endpoint::success('Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }
}
