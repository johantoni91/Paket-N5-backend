<?php

namespace App\Api;

class Endpoint
{
    public static function failed($status, $msg, $error = '')
    {
        return response()->json([
            'status'    => false,
            'message'   => $msg,
            'error'     => $error
        ], $status);
    }

    public static function success($status, $msg, $data = '')
    {
        return response()->json([
            'status'    => true,
            'message'   => $msg,
            'data'      => $data
        ], $status);
    }

    public static function warning($status, $msg, $data = '')
    {
        return response()->json([
            'status'    => false,
            'message'   => $msg,
            'data'      => $data
        ], $status);
    }
}
