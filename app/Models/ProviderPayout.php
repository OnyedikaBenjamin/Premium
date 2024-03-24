<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPayout extends Model
{
    use HasFactory;
    protected $table = 'provider_payouts';
    protected $fillable = [
        'provider_id', 'payment_method', 'description','amount','status','paid_date','bank_id',
    ];
    protected $casts = [
        'provider_id'     => 'integer',
        'amount'    => 'double',
    ];
    public function providers(){
        return $this->belongsTo(User::class, 'provider_id','id');
    }
    public function scopeMyPayout($query)
    {
        if(auth()->user()->hasRole('admin')) {
            return $query;
        }

        if(auth()->user()->hasRole('provider')) {
            return $query->where('provider_id', \Auth::id());
        }

        return $query;
    }

}
