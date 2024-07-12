<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Helpers\Log as HelpersLog;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function getLog($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if ($user->roles == 'superadmin') {
                $logs = Log::orderBy('created_at', 'desc')->paginate(5);
            } else {
                $logs = Log::orderBy('created_at', 'desc')->where('users_id', $user->id)->paginate(5);
            }
            return Endpoint::success('Berhasil mendapatkan data log aktivitas', $logs);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan data log aktivitas');
        }
    }

    function getColumn(Request $req)
    {
        try {
            $columns = Log::orderBy($req->column, 'desc')->select($req->column)->distinct()->get();
            return Endpoint::success('Berhasil mendapatkan Kolom', $columns);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan kolom');
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
                'users_id'          => $req->users_id,
                'pagination'        => $req->pagination
            ];
            $data = HelpersLog::query($input);
            if (!$data) {
                return Endpoint::success('Tidak ada log aktivitas');
            }
            return Endpoint::success('Berhasil', $data);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal mendapatkan log aktivitas!');
        }
    }
}
