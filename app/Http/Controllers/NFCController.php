<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Kartu;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Models\Satker;
use App\Models\Signature;
use Illuminate\Http\Request;

class NFCController extends Controller
{
    function retrieveAndUpdate(Request $req)
    {
        try {
            $check = Pengajuan::where('uid_kartu', $req->uid)->first();
            if ($check) {
                return Endpoint::warning('Kartu sudah ada');
            } else {
                $update = Pengajuan::where('token', $req->token)->update([
                    'uid_kartu' => $req->uid,
                    'token' => ''
                ]);
                if ($update) {
                    return Endpoint::success('Berhasil disimpan', Pengajuan::where('uid_kartu', $req->uid)->first());
                }
            }
        } catch (\Throwable $th) {
            return Endpoint::failed('Terjadi kesalahan');
        }
    }

    function findUid($uid)
    {
        try {
            $info = Pengajuan::where('uid_kartu', $uid)->first();
            if ($info) {
                return Endpoint::success('Berhasil ditemukan', $info);
            }
            return Endpoint::failed('Tidak ditemukan');
        } catch (\Throwable $th) {
            return Endpoint::failed('Terjadi kesalahan');
        }
    }

    function findPegawaiByUid($uid)
    {
        try {
            $info = Pengajuan::where('uid_kartu', $uid)->first();
            $user = Pegawai::where('nip', $info->nip)->first();
            $satker = Satker::where('satker_name', 'LIKE', '%' . $user->nama_satker . '%')->where('satker_code', 'NOT LIKE', null)->first();
            $kartu = Kartu::where('id', $info->kartu)->first();
            $ttd = Signature::where('satker', $satker->satker_code)->first();
            $res = [
                'pengajuan' => $info,
                'pegawai' => $user,
                'kartu' => $kartu,
                'ttd' => $ttd
            ];
            return Endpoint::success('Pegawai berhasil ditemukan', $res);
        } catch (\Throwable $th) {
            return Endpoint::failed('Terjadi kesalahan');
        }
    }
}
