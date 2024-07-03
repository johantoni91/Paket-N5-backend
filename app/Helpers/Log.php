<?php

namespace App\Helpers;

use App\Models\Log as ModelsLog;

class Log
{
    public static function insert($user_id, $username, $ip_address, $browser, $browser_version, $os, $mobile, $log_detail)
    {
        $log = [
            'id'                => mt_rand(),
            'users_id'          => $user_id,
            'username'          => $username,
            'ip_address'        => $ip_address,
            'browser'           => $browser,
            'browser_version'   => $browser_version,
            'os'                => $os,
            'mobile'            => $mobile,
            'log_detail'        => $log_detail
        ];
        return ModelsLog::insert($log);
    }

    public static function query($input)
    {
        if ($input['start'] == null) {
            return ModelsLog::orderBy('created_at', 'desc')
                ->where('username', 'LIKE', '%' . $input['username'] . '%')
                ->where('ip_address', 'LIKE', '%' . $input['ip_address'] . '%')
                ->where('browser', 'LIKE', '%' . $input['browser'] . '%')
                ->where('browser_version', 'LIKE', '%' . $input['browser_version'] . '%')
                ->where('os', 'LIKE', '%' . $input['os'] . '%')
                ->where('mobile', 'LIKE', '%' . $input['mobile'] . '%')
                ->where('log_detail', 'LIKE', '%' . $input['log_detail'] . '%')
                ->paginate($input['pagination'])->appends([
                    'users_id' => $input['users_id'],
                    'username'  => $input['username'],
                    'ip_address' => $input['ip_address'],
                    'browser'  => $input['browser'],
                    'browser_version'  => $input['browser_version'],
                    'os'  => $input['os'],
                    'mobile'  => $input['mobile'],
                    'log_detail'  => $input['log_detail'],
                    'pagination'  => $input['pagination']
                ]);
        } else {
            return ModelsLog::where('username', 'LIKE', '%' . $input['username'] . '%')
                ->where('ip_address', 'LIKE', '%' . $input['ip_address'] . '%')
                ->where('browser', 'LIKE', '%' . $input['browser'] . '%')
                ->where('browser_version', 'LIKE', '%' . $input['browser_version'] . '%')
                ->where('os', 'LIKE', '%' . $input['os'] . '%')
                ->where('mobile', 'LIKE', '%' . $input['mobile'] . '%')
                ->where('log_detail', 'LIKE', '%' . $input['log_detail'] . '%')
                ->whereBetween('created_at', [$input['start'], $input['end']])
                ->paginate($input['pagination'])->appends([
                    'users_id' => $input['users_id'],
                    'username'  => $input['username'],
                    'ip_address' => $input['ip_address'],
                    'browser'  => $input['browser'],
                    'browser_version'  => $input['browser_version'],
                    'os'  => $input['os'],
                    'mobile'  => $input['mobile'],
                    'log_detail'  => $input['log_detail'],
                    'pagination'  => $input['pagination']
                ]);
        }
    }
}
