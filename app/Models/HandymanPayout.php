<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandymanPayout extends Model
{
    use HasFactory;
    protected $table = 'handyman_payouts';
    protected $fillable = [
        'handyman_id', 'payment_method', 'description','amount'
    ];

    protected $casts = [
        'handyman_id'     => 'integer',
        'amount'    => 'double',
    ];
    public function handymans(){
        return $this->belongsTo(User::class, 'handyman_id','id');
    }
    public function scopeMyPayout($query)
    {
        if(auth()->user()->hasRole('admin')) {
            return $query;
        }

        if(auth()->user()->hasRole('handyman')) {
            return $query->where('handyman_id', \Auth::id());
        }

        return $query;
    }
}
