<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\Log;
use App\Helpers\SatkerCode;
use App\Models\Pegawai;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PegawaiController extends Controller
{
    private $pegawai = '(Pegawai)';
    public function index($id)
    {
        try {
            $satker = str_replace('-', ' ', $id);
            if ($satker == "kejaksaan agung" || $satker == "kejaksaan agung republik indonesia") {
                $data = Pegawai::orderBy('nama')->paginate(5);
            } else {
                $data = Pegawai::orderBy('nama', 'asc')->where('nama_satker', $satker)->paginate(5);
            }
            return Endpoint::success('Berhasil mendapatkan data pegawai', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data pegawai');
        }
    }

    public function search(Request $req, $id)
    {
        try {
            $satker_code = SatkerCode::parent($id);
            $nama_satker = Satker::where('satker_code', 'LIKE', '%' . $id . '%')->first();
            if ($satker_code == '0') {
                $pegawai = Pegawai::orderBy('nama')
                    ->where('nama', 'LIKE', '%' . $req->nama . '%')
                    ->where('nip', 'LIKE', '%' . $req->nip . '%')
                    ->where('nrp', 'LIKE', '%' . $req->nrp . '%')
                    ->paginate($req->pagination ?? 5)->appends([
                        'nama'       => $req->nama,
                        'nip'        => $req->nip,
                        'nrp'        => $req->nrp,
                        'pagination' => $req->pagination
                    ]);
            } else {
                $pegawai = Pegawai::orderBy('nama')
                    ->where('nama_satker', 'LIKE',  '%' . $nama_satker->satker_name . '%')
                    ->where('nama', 'LIKE', '%' . $req->nama . '%')
                    ->where('nip', 'LIKE', '%' . $req->nip . '%')
                    ->where('nrp', 'LIKE', '%' . $req->nrp . '%')
                    ->paginate($req->pagination ?? 5)->appends([
                        'nama'       => $req->nama,
                        'nip'        => $req->nip,
                        'nrp'        => $req->nrp,
                        'pagination' => $req->pagination
                    ]);
            }
            if (!$pegawai) {
                return Endpoint::warning('Tidak menemukan data pegawai');
            }
            return Endpoint::success('Berhasil mendapatkan data pegawai', $pegawai);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
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
            return Endpoint::success('Berhasil menambahkan data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed('Berhasil menambahkan data pegawai. Mohon cek kembali data yang diinputkan dan masukkan data yang valid.');
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $data = [
                'nama'           =>  $req->nama ?? '',
                'jabatan'        =>  $req->jabatan ?? '',
                'nip'            =>  $req->nip ?? '',
                'nrp'            =>  $req->nrp ?? '',
                'tgl_lahir'      =>  $req->tgl_lahir ?? '',
                'eselon'         =>  $req->eselon ?? '',
                'GOL_KD'         =>  $req->GOL_KD ?? '',
                'golpang'        =>  $req->golpang ?? '',
                'jaksa_tu'       =>  $req->jaksa_tu ?? '',
                'struktural_non' =>  $req->struktural_non ?? '',
                'jenis_kelamin'  =>  $req->jenis_kelamin ?? '',
                'nama_satker'    =>  $req->nama_satker ?? '',
                'agama'          =>  $req->agama ?? '',
                'status_pegawai' =>  $req->status_pegawai ?? '',
            ];

            $pegawai = Pegawai::find($id);
            if ($req->hasFile('photo')) {
                if (File::exists(parse_url($pegawai->foto_pegawai)['path'])) {
                    unlink('../public' . parse_url($pegawai->foto_pegawai)['path']);
                }
                $fileName = $req->file('photo')->getClientOriginalName();
                $req->file('photo')->move('pegawai', $fileName);
                $data = [
                    'foto_pegawai'   =>  env('APP_IMG', '') . '/pegawai/' . $fileName,
                ];
            }

            Pegawai::where('nip', $id)->orWhere('nrp', $id)->update($data);
            Log::insert($req->users_id, $req->username, $req->ip_address, $req->browser, $req->browser_version, $req->os, $req->mobile, $this->pegawai . ' Ubah Pegawai.');
            return Endpoint::success('Berhasil ubah data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal ubah data pegawai');
        }
    }

    public function find($id)
    {
        try {
            return Endpoint::success('Berhasil mendapatkan data pegawai', Pegawai::where('nip', $id)->orWhere('nrp', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data pegawai');
        }
    }

    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::find($id);
            if (File::exists(parse_url($pegawai->foto_pegawai)['path'])) {
                unlink('../public' . parse_url($pegawai->foto_pegawai)['path']);
            }
            $pegawai->delete();
            return Endpoint::success('Berhasil menghapus data pegawai');
        } catch (\Throwable $th) {
            return Endpoint::failed('data pegawai tidak ditemukan');
        }
    }
}
