<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BookingActivity extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'booking_activities';
    protected $fillable = [
        'datetime', 'booking_id', 'activity_type', 'activity_message', 'activity_data'
    ];

    protected $casts = [
        'booking_id'   => 'integer',
    ];
}
