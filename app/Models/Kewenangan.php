<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kewenangan extends Model
{
    protected $table = 'kewenangan';
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // public function satker()
    // {
    //     return $this->belongsTo(Satker::class, 'users_id');
    // }
}
