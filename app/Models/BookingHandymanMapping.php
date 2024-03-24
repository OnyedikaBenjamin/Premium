<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingHandymanMapping extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'booking_handyman_mappings';
    protected $fillable = [
        'booking_id', 'handyman_id'
    ];
    
    protected $casts = [
        'booking_id'    => 'integer',
        'handyman_id'   => 'integer',
    ];
    
    public function handyman(){
        return $this->belongsTo(User::class,'handyman_id', 'id');
    }
    public function bookings(){
        return $this->belongsTo(Booking::class,'booking_id', 'id');
    }
}
