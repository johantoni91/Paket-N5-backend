<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\Log;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    private $pegawai = '(Pegawai)';
    public function index($id)
    {
        try {
            $satker = str_replace('-', ' ', $id);
            if ($satker == "KEJAKSAAN AGUNG") {
                $data = Pegawai::orderBy('nama')->paginate(10);
            } else {
                $data = Pegawai::orderBy('nama', 'asc')->where('nama_satker', ucwords($satker))->paginate(10);
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai');
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
                ->paginate(10)->appends([
                    'nama'           =>  $req->nama,
                    'jabatan'        =>  $req->jabatan,
                    'nip'            =>  $req->nip,
                    'nrp'            =>  $req->nrp,
                    'jenis_kelamin'  =>  $req->jenis_kelamin,
                    'nama_satker'    =>  $req->nama_satker,
                    'agama'          =>  $req->agama,
                    'status_pegawai' =>  $req->status_pegawai,
                ]);
            if (!$data) {
                return Endpoint::warning(200, 'Pegawai tidak ada');
            }
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    public function store(Request $req)
    {
        try {
            $fileName = $req->file('photo')->getClientOriginalName();
            $data = [
                'foto_pegawai'   =>  env('APP_IMG', '') . '/pegawai/' . $fileName,
                'nama'           =>  $req->nama,
                'jabatan'        =>  $req->jabatan,
                'nip'            =>  $req->nip,
                'nrp'            =>  $req->nrp,
                'tgl_lahir'      =>  $req->tgl_lahir,
                'eselon'         =>  $req->eselon,
                'GOL_KD'         =>  $req->GOL_KD,
                'golpang'        =>  $req->golpang,
                'jaksa_tu'       =>  $req->jaksa_tu,
                'struktural_non' =>  $req->struktural_non,
                'jenis_kelamin'  =>  $req->jenis_kelamin,
                'nama_satker'    =>  $req->nama_satker,
                'agama'          =>  $req->agama,
                'status_pegawai' =>  $req->status_pegawai,
            ];
            Pegawai::insert($data);
            $req->file('photo')->move('pegawai', $fileName);
            Log::insert($req->users_id, $req->username, $req->ip_address, $req->browser, $req->browser_version, $req->os, $req->mobile, $this->pegawai . ' Tambah Pegawai.');
            return Endpoint::success(200, 'Berhasil menambahkan data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data pegawai', $th->getMessage());
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $data = [];
            $data = [
                'nama'           =>  $req->nama,
                'jabatan'        =>  $req->jabatan,
                'nip'            =>  $req->nip,
                'nrp'            =>  $req->nrp,
                'tgl_lahir'      =>  $req->tgl_lahir,
                'eselon'         =>  $req->eselon,
                'GOL_KD'         =>  $req->GOL_KD,
                'golpang'        =>  $req->golpang,
                'jaksa_tu'       =>  $req->jaksa_tu,
                'struktural_non' =>  $req->struktural_non,
                'jenis_kelamin'  =>  $req->jenis_kelamin,
                'nama_satker'    =>  $req->nama_satker,
                'agama'          =>  $req->agama,
                'status_pegawai' =>  $req->status_pegawai,
            ];

            $pegawai = Pegawai::find($id);
            if ($req->hasFile('photo')) {
                unlink('../public' . parse_url($pegawai->foto_pegawai)['path']);
                $fileName = $req->file('photo')->getClientOriginalName();
                $req->file('photo')->move('pegawai', $fileName);
                $data = [
                    'foto_pegawai'   =>  env('APP_IMG', '') . '/pegawai/' . $fileName,
                ];
            }

            $pegawai->update($data);
            Log::insert($req->users_id, $req->username, $req->ip_address, $req->browser, $req->browser_version, $req->os, $req->mobile, $this->pegawai . ' Ubah Pegawai.');
            return Endpoint::success(200, 'Berhasil ubah data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal ubah data pegawai', $th->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', Pegawai::where('nip', $id)->orWhere('nip', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai');
        }
    }

    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::find($id);
            unlink('../public' . parse_url($pegawai->foto_pegawai)['path']);
            $pegawai->delete();
            return Endpoint::success(200, 'Berhasil menghapus data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'data pegawai tidak ditemukan');
        }
    }
}
