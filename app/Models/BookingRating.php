<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BookingRating extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'booking_ratings';
    protected $fillable = [
        'booking_id', 'customer_id', 'service_id' , 'rating', 'review'
    ];

    protected $casts = [
        'booking_id'    => 'integer',
        'service_id'    => 'integer',
        'customer_id'   => 'integer',
        'rating'        => 'double',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function service(){
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    protected static function boot(){
        parent::boot();
        static::deleted(function ($row) {
            if($row->forceDeleting === true)
            {
                $row->forceDelete();
            }
        });
        static::restoring(function($row) {
            $row->withTrashed()->restore();
        });
    }

    public function scopeMyRating($query){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            $query =  $query;
        }

        if($user->hasRole('provider')) {
            $query = $query->whereHas('service',function ($q) use($user) {
                $q->where('provider_id',$user->id);
            });
        }

        return  $query;
    }
}
