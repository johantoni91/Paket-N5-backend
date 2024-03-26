<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PegawaiController extends Controller
{
    private $pegawai = '(Pegawai)';
    public function index()
    {
        try {
            $data = Pegawai::orderBy('nama', 'asc')->paginate(10);
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
            $req->file('photo')->move('pegawai', $fileName);
            $data = [
                'foto_pegawai'   =>  env('APP_ENV', '') == 'production' ? env('APP_IMG', '') . '/pegawai/' . $fileName : env('APP_URL', '') . '/pegawai/' . $fileName,
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

            $this->validate($req, [
                'nip'   => 'unique:App\Models\Pegawai,nip',
                'nrp'   => 'unique:App\Models\Pegawai,nrp'
            ], [
                'nip.unique' => 'NIP sudah ada',
                'nrp.unique' => 'NRP sudah ada',
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
                'log_detail'        => $this->pegawai . ' Tambah Pegawai.',
            ];
            Log::insert($log);
            $pegawai = Pegawai::insert($data);
            if (!$pegawai) {
                return Endpoint::warning(400, 'Gagal menambahkan data pegawai');
            }
            return Endpoint::success(200, 'Berhasil menambahkan data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan data pegawai', 'Mohon isi field yang masih kosong');
        }
    }

    public function update(Request $req, $nip)
    {
        try {
            $data = [];
            $pegawai = Pegawai::find($nip);
            if ($req->hasFile('photo')) {
                File::exists($pegawai->foto_pegawai) ? File::delete($pegawai->foto_pegawai) : '';
                $fileName = $req->file('photo')->getClientOriginalName();
                $req->file('photo')->move('pegawai', $fileName);
                $data = [
                    'foto_pegawai'   =>  env('APP_ENV', '') == 'production' ? env('APP_IMG', '') . '/pegawai/' . $fileName : env('APP_URL', '') . '/pegawai/' . $fileName,
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
            } else {
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
            }
            $log = [
                'id'                => mt_rand(),
                'users_id'          => $req->users_id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->pegawai . ' Ubah Pegawai.',
            ];

            Log::insert($log);
            $pegawai->update($data);
            if (!$pegawai) {
                return Endpoint::warning(400, 'Gagal ubah data pegawai');
            }
            return Endpoint::success(200, 'Berhasil ubah data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal ubah data pegawai');
        }
    }

    public function findPegawai($id)
    {
        try {
            return Endpoint::success(200, 'Berhasil mendapatkan data pegawai', Pegawai::find($id));
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data pegawai');
        }
    }

    public function destroy($nip)
    {
        try {
            $pegawai = Pegawai::find($nip);
            File::exists($pegawai->foto_pegawai) ? File::delete($pegawai->foto_pegawai) : '';
            $pegawai->delete();
            return Endpoint::success(200, 'Berhasil menghapus data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'data pegawai tidak ditemukan');
        }
    }

    public function import(Request $req)
    {
        try {
        } catch (\Throwable $th) {
        }
    }
}
