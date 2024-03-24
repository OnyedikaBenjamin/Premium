<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    use HasFactory;
    protected $table = 'subscription_transactions';
    protected $fillable = [
        'subscription_plan_id','user_id', 'amount', 'payment_type','txn_id', 'payment_status','other_transaction_detail'
    ];
    protected $casts = [
        'amount'    => 'double',
        'user_id'    => 'integer',
        'subscription_plan_id'    => 'integer',
    ];
}
