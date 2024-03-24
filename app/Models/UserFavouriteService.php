<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFavouriteService extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
       'service_id' , 'user_id'
    ];

    protected $casts = [
        'service_id'    => 'integer',
        'user_id'       => 'integer',
    ];
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function service(){
        return $this->belongsTo(Service::class, 'service_id', 'id')->withTrashed();
    }
}
