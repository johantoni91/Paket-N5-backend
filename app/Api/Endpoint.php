<?php

namespace App\Api;

class Endpoint
{
    public static function failed($status, $msg, $error = null)
    {
        return response()->json([
            'status'    => $status,
            'message'   => $msg,
            'error'     => $error
        ], $status);
    }

    public static function success($status, $msg, $data = null)
    {
        return response()->json([
            'status'    => $status,
            'message'   => $msg,
            'data'      => $data
        ], $status);
    }
}
