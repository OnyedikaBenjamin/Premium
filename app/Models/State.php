<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = "states";
    protected $primaryKey = "id";
    
    protected $casts = [
        'country_id'    => 'integer',
    ];
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id','id');
    }

	public function cities()
    {
        return $this->hasMany(City::class, 'country_id','id');
    }
}
