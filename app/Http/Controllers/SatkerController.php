<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\SatkerCode;
use App\Models\Log;
use App\Models\Satker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SatkerController extends Controller
{
    private $satker = '(Satker)';
    function index($satker)
    {
        try {
            $satker_code = SatkerCode::parent($satker);
            if ($satker_code == '0') {
                $satker = Satker::orderBy('satker_type')->where('satker_code', 'NOT LIKE', null)->paginate(5);
            } else {
                $satker = Satker::orderBy('satker_type')->where('satker_code', 'NOT LIKE', null)->where('satker_code', 'like', $satker . '%')->paginate(5);
            }
            return Endpoint::success('mendapatkan data satker', $satker);
        } catch (\Throwable $th) {
            return Endpoint::failed("gagal mendapatkan data satker");
        }
    }

    function name(Request $req)
    {
        try {
            $satker = Satker::where('satker_code', 'NOT LIKE', null)->where('satker_name', 'LIKE', '%' . $req->satker . '%')->limit(5)->get();
            return Endpoint::success('Berhasil', $satker);
        } catch (\Throwable $th) {
            return Endpoint::warning('Satker tidak ditemukan');
        }
    }

    function getSatker()
    {
        try {
            return Endpoint::success('Berhasil mendapatkan satker', Satker::orderBy('satker_name', 'desc')->select('satker_name')->distinct()->get());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan satker');
        }
    }

    function all()
    {
        return Endpoint::success('Berhasil', Satker::select('satker_code', 'satker_name')->orderBy('satker_name', 'desc')->where('satker_code', 'NOT LIKE', null)->get());
    }

    function search(Request $req)
    {
        try {
            $data = Satker::orderBy('satker_type')
                ->where('satker_code', 'NOT LIKE', null)
                ->where('satker_name', 'LIKE', '%' . $req->satker_name . '%')
                ->where('satker_type', $req->satker_type)
                ->paginate(5)->appends([
                    'satker_name'    => $req->satker_name,
                    'satker_type'    => $req->satker_type,
                ]);
            if (!$data) {
                return Endpoint::success('Satker tidak ada');
            }
            return Endpoint::success('Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan Satker!');
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
            return Endpoint::success('Berhasil menemukan data satker!', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function findByCode($code)
    {
        try {
            $data = Satker::where('satker_code', $code)->first();
            return Endpoint::success('Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function findByName(Request $req)
    {
        try {
            return Endpoint::success('Berhasil', Satker::where('satker_name', 'LIKE', '%' . $req->satker . '%')->where('satker_code', 'NOT LIKE', null)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function status($id, $stat)
    {
        try {
            $status = Satker::where('id', $id)->first();
            if (!$status) {
                return Endpoint::failed('Satker tidak ditemukan');
            }
            $status->satker_status = $stat == "1" ? '0' : '1';
            $status->save();
            return Endpoint::success('Berhasil mengubah status');
        } catch (\Throwable $th) {
            return Endpoint::failed('Terjadi kesalahan!');
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
            return Endpoint::success('Berhasil menambahkan satker', Satker::latest()->first());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menambahkan satker');
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
            return Endpoint::success('Berhasil mengubah  satker', Satker::where('id', $id)->first());
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mengubah satker');
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
            return Endpoint::success('Berhasil menghapus data satker!');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal menghapus data satker!');
        }
    }
}
