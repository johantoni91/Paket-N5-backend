<?php

namespace App\Api;

class Endpoint
{
    public static function success($msg, $data = '')
    {
        return response()->json([
            'status'    => true,
            'message'   => $msg,
            'data'      => $data
        ], 200);
    }

    public static function warning($msg, $data = '')
    {
        return response()->json([
            'status'    => false,
            'message'   => $msg,
            'data'      => $data
        ], 200);
    }

    public static function failed($msg, $error = '')
    {
        return response()->json([
            'status'    => false,
            'message'   => $msg,
            'error'     => $error
        ], 400);
    }
}
