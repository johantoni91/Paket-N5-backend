<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Pegawai;
use App\Validation\Validate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index()
    {
        try {
            $data = Pegawai::orderBy('nama')->paginate(10);
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai', $th->getMessage());
        }
    }

    public function search(Request $req)
    {
        try {
            $data = Pegawai::orderBy('nama')
                ->where('nama', 'LIKE', '%' . $req->nama . '%')
                ->where('jabatan', 'LIKE', '%' . $req->jabatan . '%')
                ->where('nip', 'LIKE', '%' . $req->nip . '%')
                ->where('nrp', 'LIKE', '%' . $req->nrp . '%')
                ->where('jenis_kelamin', 'LIKE', '%' . $req->jenis_kelamin . '%')
                ->where('nama_satker', 'LIKE', '%' . $req->nama_satker . '%')
                ->where('agama', 'LIKE', '%' . $req->agama . '%')
                ->where('status_pegawai', 'LIKE', '%' . $req->status_pegawai . '%')
                ->paginate(50);

            if (!$data) {
                return Endpoint::warning(200, 'Pegawai tidak ada');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
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

            return Endpoint::success(200, 'Berhasil menambahkan data pegawai', Pegawai::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data pegawai', $th->getMessage());
        }
    }

    public function findPegawai($id)
    {
        try {
            Pegawai::find($id);
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', Pegawai::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai', $th->getMessage());
        }
    }

    public function deletePegawai($id)
    {
        try {
            Pegawai::find($id)->delete();
            return Endpoint::success(200, 'Berhasil menghapus data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'data pegawai tidak ditemukan', $th->getMessage());
        }
    }
}
