<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\Log as HelpersLog;
use App\Models\Log;
use App\Models\Satker;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function getLog($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if ($user->roles == 'superadmin') {
                $logs = Log::orderBy('created_at', 'desc')->paginate(10);
            } else {
                $logs = Log::orderBy('created_at', 'desc')->where('users_id', $user->id)->paginate(10);
            }
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
            $input = [
                'username'          => $req->username,
                'ip_address'        => $req->ip_address,
                'browser'           => $req->browser,
                'browser_version'   => $req->browser_version,
                'os'                => $req->os,
                'mobile'            => $req->mobile,
                'log_detail'        => $req->log_detail,
                'start'             => $req->start,
                'end'               => $req->end,
                'users_id'          => $req->users_id
            ];
            $data = HelpersLog::query($input);
            if (!$data) {
                return Endpoint::success(200, 'Tidak ada log aktivitas');
            }
            return Endpoint::success(200, 'Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed(400, 'Gagal mendapatkan log aktivitas!', $th->getMessage());
        }
    }
}
