<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;
    protected $table = 'payment_histories';
    protected $fillable = [ 'payment_id', 'booking_id',
        'action', 'text', 'type', 'sender_id', 'receiver_id', 'datetime', 'status','total_amount',
        'txn_id','other_transaction_detail','parent_id'
        ];

    protected $casts = [
        'payment_id'    => 'integer',
        'booking_id'   => 'integer',
        'sender_id'      => 'integer',
        'parent_id'      => 'integer',
        'total_amount'  => 'double',
        'receiver_id'  => 'integer',
    ];

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id', 'id')->withTrashed();
    }
    public function sender(){
        return $this->belongsTo(User::class,'sender_id', 'id')->withTrashed();
    }
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id', 'id')->withTrashed();
    }
    public function scopeMyHistory($query)
    {
        $user = auth()->user();
        if($user->hasAnyRole(['admin', 'demo_admin'])){
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->where('receiver_id',$user->id);
        }

        if($user->hasRole('user')) {
            return $query->where('customer_id', $user->id);
        }

        if($user->hasRole('handyman')) {
            return $query->where('receiver_id', $user->id);
        }

        return $query;
    }
}
