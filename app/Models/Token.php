<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function logs()
    {
        return $this->belongsTo(Log::class, 'users_id');
    }
}
