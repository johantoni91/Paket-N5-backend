<?php

namespace App\Helpers;

class Responses
{
    public static function Response($status, $message, $data = null, $token = null): array
    {
        if (!$data) {
            return [
                'status'    => $status,
                'message'   => $message
            ];
        } elseif (!$token) {
            return [
                'status'    => $status,
                'message'   => $message,
                'data'      => $data
            ];
        }
        return [
            'status'    => $status,
            'message'   => $message,
            'data'      => $data,
            'token'     => $token
        ];
    }
}
