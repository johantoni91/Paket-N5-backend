<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Log;
use App\Models\Satker;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function getLog()
    {
        try {
            $logs = Log::orderBy('created_at', 'desc')->paginate(10);
            return Endpoint::success(200, 'Berhasil mendapatkan data log aktivitas', $logs);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan data log aktivitas', $th->getMessage());
        }
    }

    function getColumn(Request $req)
    {
        try {
            $columns = Log::orderBy($req->column, 'desc')->select($req->column)->distinct()->get();
            return Endpoint::success(200, 'Berhasil mendapatkan Kolom', $columns);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan kolom', $th->getMessage());
        }
    }

    public function search(Request $req)
    {
        try {
            $data = Log::orderBy('created_at', 'desc')
                ->where('username', 'LIKE', '%' . $req->username . '%')
                ->where('ip_address', 'LIKE', '%' . $req->ip_address . '%')
                ->where('browser', 'LIKE', '%' . $req->browser . '%')
                ->where('browser_version', 'LIKE', '%' . $req->browser_version . '%')
                ->where('os', 'LIKE', '%' . $req->os . '%')
                ->where('mobile', 'LIKE', '%' . $req->mobile . '%')
                ->where('log_detail', 'LIKE', '%' . $req->log_detail . '%')
                ->whereBetween('created_at', [$req->start, $req->end])
                ->paginate(10)->appends([
                    'users_id' => $req->users_id,
                    'username'  => $req->username,
                    'ip_address' => $req->ip_address,
                    'browser'  => $req->browser,
                    'browser_version'  => $req->browser_version,
                    'os'  => $req->os,
                    'mobile'  => $req->mobile,
                    'log_detail'  => $req->log_detail
                ]);
            // dd($data);
            if (!$data) {
                return Endpoint::success(200, 'Tidak ada log aktivitas');
            }
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan log aktivitas!', $th->getMessage());
        }
    }
}
