<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Faq;
use App\Models\Kartu;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Models\Rate;
use App\Models\Satker;
use App\Models\User;

class DashboardController extends Controller
{
    function index($id)
    {
        try {
            $satker_code = SatkerCode::parent($id);
            $satker_name = Satker::where('satker_code', $id)->first();
            if ($satker_code == '0') {
                return Endpoint::success('Berhasil', [
                    'user' => User::count(),
                    'pegawai' => Pegawai::count(),
                    'satker' => Satker::count(),
                    'pengajuan' => Pengajuan::count(),
                    'kartu' => Kartu::count(),
                    'faq' => Faq::count(),
                    'rating' => Rate::count()
                ]);
            } else {
                return Endpoint::success('Berhasil', [
                    'user' => User::where('satker', $id)->count(),
                    'pegawai' => Pegawai::where('nama_satker', str_replace('-', ' ', $satker_name))->count(),
                    'satker' => Satker::where('satker_code', 'LIKE', $id . '%')->count(),
                    'pengajuan' => Pengajuan::where('kode_satker', $id)->count(),
                    'kartu' => Kartu::count(),
                    'faq' => Faq::count(),
                    'rating' => Rate::count()
                ]);
            }
        } catch (\Throwable $th) {
            return Endpoint::failed('Error');
        }
    }
}
