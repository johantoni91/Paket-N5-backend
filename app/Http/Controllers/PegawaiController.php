<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Pegawai;
use App\Validation\Validate;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function getAllPegawai()
    {
        $pegawai = Pegawai::all();
        if (!$pegawai) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai');
        }

        return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', $pegawai);
    }

    public function storePegawai(Request $req)
    {
        $data = [
            'draw'      => $req->draw,
            'start'     => $req->start,
            'length'    => $req->length,
            'nama'      => $req->nama,
            'nip'       => $req->nip,
            'nrp'       => $req->nrp,
            'jabatan'   => $req->jabatan
        ];
        $this->validate($req, Validate::pegawai());

        $pegawai = Pegawai::insert($data);
        if (!$pegawai) {
            return Endpoint::failed(400, 'Gagal menambahkan data pegawai');
        }

        return Endpoint::success(200, 'Berhasil menambahkan data pegawai', Pegawai::latest()->first());
    }

    public function findPegawai($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai');
        }

        return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', Pegawai::latest()->first());
    }

    public function deletePegawai($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return Endpoint::failed(400, 'data pegawai tidak ditemukan');
        }

        $pegawai->delete();
        return Endpoint::success(200, 'Berhasil menghapus data pegawai');
    }
}
