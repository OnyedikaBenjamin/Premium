<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlayerIds extends Model
{
    use HasFactory;
    protected $table = 'user_player_ids';
    protected $fillable = [
        'user_id', 'player_id'
    ];
    protected $casts = [
        'user_id'        => 'integer',
    ];
}
