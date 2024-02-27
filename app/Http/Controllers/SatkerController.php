<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\Satker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SatkerController extends Controller
{
    private $satker = '(Satker)';
    function index()
    {
        try {
            $satker = Satker::all();
            return Endpoint::success(200, 'mendapatkan data satker', $satker);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, "gagal mendapatkan data satker", $th->getMessage());
        }
    }

    public function search(Request $req)
    {
        try {
            $data = Satker::where($req->category, 'LIKE', '%' . $req->search . '%')->get();
            if (!$data) {
                return Endpoint::success(200, 'Satker tidak ada');
            }
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan Satker!', $th->getMessage());
        }
    }

    public function find(Request $req, $id)
    {
        try {
            $data = Satker::where('id', $id)->first();
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->satker . ' Lihat data satker ' . $data->satker . '. Oleh ' . $req->username,
                'created_at'        => Carbon::now()
            ]);
            return Endpoint::success(200, 'Berhasil menemukan data satker!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, $th->getMessage());
        }
    }

    public function status($id, $stat)
    {
        try {
            $status = Satker::where('id', $id)->first();
            if (!$status) {
                return Endpoint::failed(400, 'Satker tidak ditemukan');
            }
            $status->satker_status = $stat == "1" ? '0' : '1';
            $status->save();
            return Endpoint::success(200, 'Berhasil mengubah status');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Terjadi kesalahan!', $th->getMessage());
        }
    }

    function store(Request $req)
    {
        try {
            $code = mt_rand();
            $akronim = "";
            if ($req->type == 0) {
                $akronim = "KJ";
            } elseif ($req->type == 1) {
                $akronim = "KT";
            } elseif ($req->type == 1) {
                $akronim = "KN";
            } else {
                $akronim = "CN";
            }
            $data = [
                'id'             => mt_rand(),
                'satker_name'    => $req->satker,
                'satker_type'    => $req->type,
                'satker_phone'   => $req->phone,
                'satker_email'   => $req->email,
                'satker_address' => $req->address,
                'satker_akronim' => $akronim . $code,
                'satker_code'    => $code
            ];
            $this->validate($req, [
                'satker'  => 'required',
                'phone'   => 'required',
                'email'   => 'required|email:rfc,dns',
                'address' => 'required',
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
                'log_detail'        => $this->satker . ' Tambah data satker.',
            ];
            Log::insert($log);
            Satker::insert($data);
            return Endpoint::success(200, 'Berhasil menambahkan satker', Satker::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menambahkan satker', $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        try {
            $data = [
                'satker_name'    => $req->satker,
                'satker_type'    => $req->type,
                'satker_phone'   => $req->phone,
                'satker_email'   => $req->email,
                'satker_address' => $req->address
            ];

            $log = [
                'id'                => mt_rand(),
                'users_id'          => $req->users_id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->satker . ' Ubah data satker.',
            ];
            Log::insert($log);
            Satker::where('id', $id)->update($data);
            return Endpoint::success(200, 'Berhasil mengubah  satker', Satker::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mengubah satker', $th->getMessage());
        }
    }

    public function delete(Request $req, $id)
    {
        try {
            $satker = Satker::where('id', $id)->first();
            Log::insert([
                'id'                => mt_rand(),
                'users_id'          => $id,
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $this->satker . ' Hapus data satker ' . $satker->satker_name,
                'created_at'        => Carbon::now()
            ]);
            $satker->delete();
            return Endpoint::success(200, 'Berhasil menghapus data satker!');
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal menghapus data satker!', $th->getMessage());
        }
    }
}
