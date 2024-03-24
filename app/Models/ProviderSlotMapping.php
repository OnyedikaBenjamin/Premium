<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderSlotMapping extends Model
{
    use HasFactory;
    protected $table = 'provider_slot_mappings';
    protected $fillable = [
       'provider_id', 'days','start_at','end_at','status'
    ];
    protected $casts = [
        'provider_id' => 'integer',
        'status' => 'integer',
    ];   
}
