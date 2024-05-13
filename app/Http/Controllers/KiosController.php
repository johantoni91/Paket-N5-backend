<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kartu;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Models\Perangkat;
use App\Models\TcHardware;
use Illuminate\Http\Request;

class KiosController extends Controller
{
    function login(Request $req)
    {
        $this->validate($req, [
            'user'      => 'required',
            'password'  => 'required'
        ]);

        $login = Perangkat::where('user', $req->user)->where('password', $req->password)->first();
        if (!$login) {
            return Endpoint::failed(400, 'Perangkat tidak ditemukan');
        }

        $data = TcHardware::where('id_perangkat', $login->id)->first();
        if (!$data) {
            return Endpoint::failed(400, 'Akun tidak ada');
        }

        $login->update([
            'status'    => '1'
        ]);
        return Endpoint::success(200, 'Berhasil masuk', $data);
    }

    function checkToken(Request $req)
    {
        try {
            $pengajuan = Pengajuan::where('token', $req->token)->first();
            if (!$pengajuan) {
                return Endpoint::failed(400, 'Gagal');
            }
            return Endpoint::success(200, 'Berhasil', $pengajuan);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function verifikasi(Request $req)
    {
        try {
            $req->file('photo')->move('pengajuan', $req->file('photo')->getClientOriginalName());
            $pengajuan = Pengajuan::where('token', $req->token)->update([
                'photo' => env('APP_IMG', '') . '/pengajuan/' . $req->file('photo')->getClientOriginalName()
            ]);
            if (!$pengajuan) {
                return Endpoint::failed(400, 'Gagal');
            }
            return Endpoint::success(200, 'Berhasil', $pengajuan);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }

    function kartu($token)
    {
        try {
            $pengajuan = Pengajuan::where('token', $token)->first();
            $kartu = Kartu::where('id', $pengajuan->kartu)->first();
            $pegawai = Pegawai::where('nip', $pengajuan->nip)->first();

            $data = [
                'pengajuan' => $pengajuan,
                'kartu'     => $kartu,
                'pegawai'   => $pegawai
            ];
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }
}
