<?php

namespace App\Api;

class Endpoint
{
    public static function failed($status, $msg, $error = null)
    {
        return response()->json([
            'status'    => false,
            'message'   => $msg,
            'error'     => $error
        ], $status);
    }

    public static function success($status, $msg, $data = null)
    {
        if ($data) {
            return response()->json([
                'status'    => true,
                'message'   => $msg,
                'data'      => $data
            ], $status);
        }
        return response()->json([
            'status'    => true,
            'message'   => $msg
        ], $status);
    }

    public static function warning($status, $msg, $data = null)
    {
        return response()->json([
            'status'    => false,
            'message'   => $msg,
            'data'      => $data
        ], $status);
    }
}
