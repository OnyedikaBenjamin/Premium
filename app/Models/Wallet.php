<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallets';
    protected $fillable = [
        'user_id', 'title', 'amount','status'
    ];
    protected $casts = [
        'user_id'  =>'integer',
        'amount'   => 'double',
        'status'   => 'integer',
    ];
    public function providers(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
