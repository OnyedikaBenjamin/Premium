<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;
    protected $table = 'payment_gateways';
    protected $fillable = [
        'title', 'type', 'status', 'is_test' , 'value','live_value'
    ];
    protected $casts = [
        'is_test'    => 'integer',
        'status'    => 'integer',
    ];

}
