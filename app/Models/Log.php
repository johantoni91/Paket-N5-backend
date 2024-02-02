<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
