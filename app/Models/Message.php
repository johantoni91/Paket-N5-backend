<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
