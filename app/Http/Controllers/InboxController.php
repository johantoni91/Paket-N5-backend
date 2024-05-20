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
            $room = Room::where('user1', $user1)->where('user2', $user2)->first();
            $room2 = Room::where('user1', $user2)->where('user2', $user1)->first();
            if (!($room && $room2)) {
                Room::create([
                    'id'    => mt_rand(),
                    'user1' => $user1,
                    'user2' => $user2
                ]);
            }
            $chat = Message::where('room_id', $room->id ?? $room2->id)->first();
            if (!$chat) {
                Message::create([
                    'room_id'   => $room->id
                ]);
            }
            return Endpoint::success('Berhasil', Message::with(['room'])->where('room_id', $room->id ?? $room2->id)->get());
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }

    function chat(Request $req, $room)
    {
        try {
            $message     = Message::where('room_id', $room)->where('message', '')->first();
            if ($message) {
                $message->update([
                    'message'       => $req->message,
                    'message_from'  => $req->message_from,
                ]);
            } else {
                Message::create([
                    'room_id'       => Room::where('id', $room)->first()['id'],
                    'message'       => $req->message,
                    'message_from'  => $req->message_from,
                ]);
            }
            return Endpoint::success('Berhasil mengirimkan pesan', Message::with(['room'])->where('room_id', $room)->get());
        } catch (\Throwable $th) {
            return Endpoint::failed($th->getMessage());
        }
    }
}
