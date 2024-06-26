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
            Integrasi::insert([
                'url'       => $req->link,
                'username'  => $req->username ?? '',
                'password'  => $req->password ?? '',
                'token'     => $req->token ?? '',
                'type'      => $req->type
            ]);
            return Endpoint::success('Berhasil menambahkan tautan');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function updateType(Request $req, $id)
    {
        try {
            Integrasi::where('id', $id)->update(['type' => $req->type]);
            return Endpoint::success('Berhasil mengubah tipe');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function update(Request $req, $id)
    {
        try {
            Integrasi::where('id', $id)->update([
                'url'       => $req->link,
                'username'  => $req->username ?? '',
                'password'  => $req->password ?? '',
                'token'     => $req->token ?? '',
            ]);
            return Endpoint::success('Berhasil mengubah data tautan integrasi');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function delete($id)
    {
        try {
            $link = Integrasi::find($id);
            $link->delete();
            return Endpoint::success('Berhasil menghapus tautan');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function integration(Request $req)
    {
        try {
            $integrasi = Integrasi::where('id', $req->id)->first();
            if ($integrasi->type == 'auth') {
                $result = Http::timeout(-1)->withBasicAuth($integrasi->username, $integrasi->password)->get($integrasi->url);
                if ($result->failed()) {
                    return Endpoint::failed('Gagal', 'Mohon perbaiki data pada tautan integrasi.');
                }
                $data = $result->json()['data'];
            } elseif ($integrasi->type == 'token') {
                $result = Http::timeout(-1)->withToken($integrasi->token)->get($integrasi->url);
                if ($result->failed()) {
                    return Endpoint::failed('Gagal', 'Mohon perbaiki data pada tautan integrasi.');
                }
                $data = $result->json()['data'];
            } else {
                $result = Http::timeout(-1)->get($integrasi->url);
                if ($result->failed()) {
                    return Endpoint::failed('Gagal', 'Mohon perbaiki data pada tautan integrasi.');
                }
                $data = $result->json()['data'];
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
            return Endpoint::failed('Gagal');
        }
    }
}
