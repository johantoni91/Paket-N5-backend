<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Faq;
use App\Models\Kartu;
use App\Models\Kewenangan;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Models\Rate;
use App\Models\Satker;

class DashboardController extends Controller
{
    function index($id, $nama)
    {
        $satker_code = SatkerCode::parent($id);
        if ($satker_code == '0') {
            return Endpoint::success(200, 'Berhasil', [
                'user' => Kewenangan::count(),
                'pegawai' => Pegawai::count(),
                'satker' => Satker::count(),
                'pengajuan' => Pengajuan::count(),
                'kartu' => Kartu::count(),
                'faq' => Faq::count(),
                'rating' => Rate::count()
            ]);
        } else {
            return Endpoint::success(200, 'Berhasil', [
                'user' => Kewenangan::where('satker', $id)->count(),
                'pegawai' => Pegawai::where('nama_satker', str_replace('-', ' ', $nama))->count(),
                'satker' => Satker::where('satker_code', 'LIKE', $id . '%')->count(),
                'pengajuan' => Pengajuan::where('kode_satker', $id)->count(),
                'kartu' => Kartu::count(),
                'faq' => Faq::count(),
                'rating' => Rate::count()
            ]);
        }
    }
}
