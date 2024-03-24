<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = "cities";
    protected $primaryKey = "id";
    
    protected $casts = [
        'state_id'  => 'integer',
    ];
    
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id','id');
    }
}
