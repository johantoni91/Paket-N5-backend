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
            $room = Room::where('from', $user1)->where('to', $user2)->first();
            $room1 = Room::where('to', $user1)->where('from', $user2)->first();
            $id = mt_rand();
            if (!($room && $room1)) {
                Room::insert([
                    'id'    => mt_rand(),
                    'from'  => $user1,
                    'to'    => $user2
                ]);
                $final_room = Room::where('from', $user1)->where('to', $user2)->first();
                $chat = Message::where('room_id', $final_room->id)->first();
                if (!$chat) {
                    Message::insert([
                        'id'        => $id,
                        'room_id'   => $final_room->id,
                        'from'      => $user1
                    ]);
                }
            }
            if ($room) {
                $chat = Message::where('room_id', $room->id)->first();
                if (!$chat) {
                    Message::insert([
                        'id'        => $id,
                        'room_id'   => $room->id,
                        'from'      => $user1
                    ]);
                }
            } elseif ($room1) {
                $chat = Message::where('room_id', $room1->id)->first();
                if (!$chat) {
                    Message::insert([
                        'id'        => $id,
                        'room_id'   => $room1->id,
                        'from'      => $user1
                    ]);
                }
            }
            return Endpoint::success('Berhasil', Message::with(['room'])->orderBy('created_at')
                ->where('room_id', $id)
                ->orWhere('room_id', $room->id ?? $room1->id)
                ->orWhere('room_id', $final_room->id)
                ->get());
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
