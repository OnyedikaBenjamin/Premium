<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BookingCouponMapping extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'booking_coupon_mappings';
    protected $fillable = [ 'booking_id', 'code', 'discount', 'discount_type' ];

    protected $casts = [
        'booking_id'    => 'integer',
        'discount'      => 'double',
    ];
}
