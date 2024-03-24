<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavouriteProvider extends Model
{
    use HasFactory;
    protected $fillable = [
       'provider_id' , 'user_id'
    ];

    protected $casts = [
        'provider_id'    => 'integer',
        'user_id'       => 'integer',
    ];
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function provider(){
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
        
    public function getUserFavouriteProvider(){
        return $this->hasMany(UserFavouriteProvider::class, 'user_id','id');
    }
}
