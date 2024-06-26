<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Message;
use App\Models\Notif;
use App\Models\Room;

class NotificationController extends Controller
{
    function index($id) // Notif pengajuan
    {
        try {
            $notif = Notif::where('satker', $id)->get();
            return Endpoint::success('Ada yang mengajukan kartu', $notif);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function find($nip)
    {
        $notif = Notif::where('nip', $nip)->first();
        return Endpoint::success('Berhasil', $notif);
    }

    function destroy($id)
    {
        try {
            Notif::where('id', $id)->delete();
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function truncate()
    {
        try {
            Notif::truncate();
            return Endpoint::success('Berhasil');
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }

    function message($id) // Notif chat
    {
        try {
            $room = Room::where('users', 'LIKE', '%' . $id . '%')->first();
            $msg = Message::where('room_id', $room->id)->where('from', '!=', $id)->where('read', '0')->get();
            return Endpoint::success('Ada pesan masuk!', $msg);
        } catch (\Throwable $th) {
            return Endpoint::failed('Gagal');
        }
    }
}
