<?php

namespace App\Helpers;

use App\Models\Log as ModelsLog;

class Log
{
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
                ->paginate(10)->appends([
                    'users_id' => $input['users_id'],
                    'username'  => $input['username'],
                    'ip_address' => $input['ip_address'],
                    'browser'  => $input['browser'],
                    'browser_version'  => $input['browser_version'],
                    'os'  => $input['os'],
                    'mobile'  => $input['mobile'],
                    'log_detail'  => $input['log_detail']
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
                ->paginate(10)->appends([
                    'users_id' => $input['users_id'],
                    'username'  => $input['username'],
                    'ip_address' => $input['ip_address'],
                    'browser'  => $input['browser'],
                    'browser_version'  => $input['browser_version'],
                    'os'  => $input['os'],
                    'mobile'  => $input['mobile'],
                    'log_detail'  => $input['log_detail']
                ]);
        }
    }
}
