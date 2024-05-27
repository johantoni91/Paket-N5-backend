<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Integrasi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IntegrasiController extends Controller
{
    function index()
    {
        try {
            $integrasi = Integrasi::orderBy('created_at')->paginate(10);
            return Endpoint::success('Berhasil', $integrasi);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function find($id)
    {
        try {
            $link = Integrasi::find($id);
            return Endpoint::success('Berhasil', $link);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function store(Request $req)
    {
        try {
            $link = Integrasi::insert([
                'url'       => $req->link,
                'user'      => $req->user,
                'password'  => $req->password,
                'token'     => $req->token
            ]);
            if (!$link) {
                return Endpoint::warning('Gagal menambahkan link');
            }
            return Endpoint::success('Berhasil menambahkan link');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function update(Request $req, $id)
    {
        try {
            $link = Integrasi::where('id', $id)->update(['url' => $req->link]);
            if (!$link) {
                return Endpoint::warning('Gagal mengubah link');
            }
            return Endpoint::success('Berhasil mengubah link');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function delete($id)
    {
        try {
            $link = Integrasi::find($id);
            $delete = $link->delete();
            if (!$delete) {
                return Endpoint::warning('Link gagal dihapus');
            }
            return Endpoint::success('Berhasil menghapus link');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function integration(Request $req)
    {
        try {
            $data = Http::timeout(-1)->get($req->link)->json()['data'];
            if (!isset($data)) {
                $data = Http::timeout(-1)->withToken('adeyJhbGciOiJIUzI1NiJ9.eyJSb2xlIjoia2FtZGFsIiwiSXNzdWVyIjoibXlzaW1rYXJpIiwiVXNlcm5hbWUiOiJtYWxpZmNoYSIsImV4cCI6MTY5Mjc4Mzk5NywiaWF0IjoxNjkyNzgzOTk3fQ.fS7sAGH5yVsAAVTBhPoarA5us_Stut72vTCAggA6oNYyG')->get($req->link)->json()['data'];
            }
            Pegawai::truncate();
            for ($i = 0; $i < count($data); $i++) {
                Pegawai::insert([
                    "nama"           => $data[$i]['nama'],
                    "jabatan"        => $data[$i]['jabatan'],
                    "nip"            => $data[$i]['nip'],
                    "nrp"            => $data[$i]['nrp'],
                    "tgl_lahir"      => $data[$i]['tgl_lahir'],
                    "eselon"         => $data[$i]['eselon'],
                    "jenis_kelamin"  => $data[$i]['jenis_kelamin'],
                    "GOL_KD"         => $data[$i]['GOL_KD'],
                    "golpang"        => $data[$i]['golpang'],
                    "foto_pegawai"   => $data[$i]['foto_pegawai'],
                    "nama_satker"    => $data[$i]['nama_satker'],
                    "agama"          => $data[$i]['agama'],
                    "status_pegawai" => $data[$i]['status_pegawai'],
                    "jaksa_tu"       => $data[$i]['jaksa_tu'],
                    "struktural_non" => $data[$i]['struktural_non']
                ]);
            }
            return Endpoint::success('Berhasil melakukan integrasi data', Pegawai::count());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal', $th->getMessage());
        }
    }
}
