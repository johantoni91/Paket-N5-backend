<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Log;
use App\Models\Satker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SatkerController extends Controller
{
    private $satker = '(Satker)';
    function index($satker)
    {
        try {
            $satker_code = SatkerCode::parent($satker);
            if ($satker_code == '0') {
                $satker = Satker::orderBy('satker_type')->where('satker_code', 'NOT LIKE', null)->paginate(10);
            } else {
                $satker = Satker::orderBy('satker_type')->where('satker_code', 'NOT LIKE', null)->where('satker_code', 'like', $satker . '%')->paginate(10);
            }
            return Endpoint::success(200, 'mendapatkan data satker', $satker);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, "gagal mendapatkan data satker", $th->getMessage());
        }
    }

    function name(Request $req)
    {
        try {
            $satker = Satker::where('satker_code', 'NOT LIKE', null)->where('satker_name', 'LIKE', '%' . $req->satker . '%')->limit(5)->get();
            return Endpoint::success(200, 'Berhasil', $satker);
        } catch (\Throwable $th) {
            return Endpoint::warning(200, 'Satker tidak ditemukan');
        }
    }

    function getSatker()
    {
        try {
            return Endpoint::success(200, 'Berhasil mendapatkan satker', Satker::orderBy('satker_name', 'desc')->select('satker_name')->distinct()->get());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan satker', $th->getMessage());
        }
    }

    function all()
    {
        return Endpoint::success(200, 'Berhasil', Satker::select('satker_code', 'satker_name')->orderBy('satker_name', 'desc')->where('satker_code', 'NOT LIKE', null)->get());
    }

    function search(Request $req)
    {
        try {
            $data = Satker::orderBy('satker_type')
                ->where('satker_code', 'NOT LIKE', null)
                ->where('satker_name', 'LIKE', '%' . $req->satker_name . '%')
                ->where('satker_type', $req->satker_type)
                ->paginate(10)->appends([
                    'satker_name'    => $req->satker_name,
                    'satker_type'    => $req->satker_type,
                ]);
            if (!$data) {
                return Endpoint::success(200, 'Satker tidak ada');
            }
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan Satker!', $th->getMessage());
        }
    }

    function find(Request $req, $id)
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

    function findByCode($code)
    {
        try {
            $data = Satker::where('satker_code', $code)->first();
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function findByName(Request $req)
    {
        try {
            return Endpoint::success(200, 'Berhasil', Satker::where('satker_name', 'LIKE', '%' . $req->satker . '%')->where('satker_code', 'NOT LIKE', null)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal');
        }
    }

    function status($id, $stat)
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

    function delete(Request $req, $id)
    {
        try {
            $satker = Satker::where('satker_id', $id)->first();
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
