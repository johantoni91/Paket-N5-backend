<?php

namespace App\Http\Controllers;

use App\Api\Endpoint;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    function room($user1, $user2)
    {
        try {
            $check_users = json_encode([$user1, $user2]);
            $check_users_swap = json_encode([$user2, $user1]);
            $room = Room::where('users', $check_users)->orWhere('users', $check_users_swap)->first();
            if (!$room) {
                Room::create([
                    'id'    => mt_rand(),
                    'users' => $check_users
                ]);
                $room = Room::where('users', $check_users)->orWhere('users', $check_users_swap)->first();
            }

            $msg = Message::where('room_id', $room->id)->get();
            return response()->json([
                'res'   => $room->id,
            ], 200);
            if (!$msg) {
                Message::insert(['id' => mt_rand(), 'room_id' => $room->id]);
                return Endpoint::success('Berhasil', Message::with(['room'])->where('room_id', $room->id)->orderBy('created_at')->get());
            }
            return Endpoint::success('Berhasil', $msg);
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function chat(Request $req, $room)
    {
        try {
            Message::with(['room'])->insert([
                'room_id'   => $room,
                'message'   => $req->message,
                'from'      => $req->from
            ]);
            return Endpoint::success('Berhasil', Message::with(['room'])->where('room_id', $room)->orderBy('created_at')->get());
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }
}
