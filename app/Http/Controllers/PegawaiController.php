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
        try {
            $pegawai = Pegawai::all();
            return Endpoint::success(true, 'Berhasil mendapatkan data pegawai', $pegawai);
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Gagal mendapatkan data pegawai', $th->getMessage());
        }
    }

    public function storePegawai(Request $req)
    {
        try {
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
            Pegawai::insert($data);

            return Endpoint::success(true, 'Berhasil menambahkan data pegawai', Pegawai::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Gagal menambahkan data pegawai', $th->getMessage());
        }
    }

    public function findPegawai($id)
    {
        try {
            Pegawai::find($id);
            return Endpoint::success(true, 'Berhasil mendapatkan data pegawai', Pegawai::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'Gagal mendapatkan data pegawai', $th->getMessage());
        }
    }

    public function deletePegawai($id)
    {
        try {
            Pegawai::find($id)->delete();
            return Endpoint::success(true, 'Berhasil menghapus data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(false, 'data pegawai tidak ditemukan', $th->getMessage());
        }
    }
}
