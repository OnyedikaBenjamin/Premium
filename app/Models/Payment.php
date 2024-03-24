<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'payments';
    protected $fillable = [ 'customer_id', 'booking_id', 'datetime', 'discount', 'total_amount', 'payment_type', 'txn_id', 'payment_status', 'other_transaction_detail' ];

    protected $casts = [
        'booking_id'    => 'integer',
        'customer_id'   => 'integer',
        'discount'      => 'double',
        'total_amount'  => 'double',
    ];
    
    public function customer(){
        return $this->belongsTo(User::class,'customer_id', 'id')->withTrashed();
    }
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id', 'id')->withTrashed();
    }
    public function scopeMyPayment($query)
    {
        $user = auth()->user();
        if($user->hasAnyRole(['admin', 'demo_admin'])){
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->whereHas('booking', function($q) use($user) {
                $q->where('provider_id', '=', $user->id);
            });
        }

        if($user->hasRole('user')) {
            return $query->where('customer_id', $user->id);
        }

        if($user->hasRole('handyman')) {
            return $query->whereHas('booking',function ($q) use($user) {
                $q->whereHas('handymanAdded',function($handyman) use($user){
                    $handyman->where('handyman_id',$user->id);
                });
            });
        }

        return $query;
    }
    public function paymentHistory(){
        return $this->hasMany(PaymentHistory::class, 'payment_id','id');
    }
}