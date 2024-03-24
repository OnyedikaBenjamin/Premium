<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    use HasFactory;
    protected $table = 'wallet_histories';
    protected $fillable = [
        'datetime', 'user_id', 'activity_type', 'activity_message', 'activity_data'
    ];

    protected $casts = [
        'user_id'   => 'integer',
    ];
    public function providers(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
