<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderType extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'provider_types';
    protected $fillable = [
        'name', 'commission', 'status','type'
    ];

    protected $casts = [
        'commission'=> 'double',
        'status'    => 'integer',
    ];
    
}
