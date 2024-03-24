<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class ServiceProof extends Model implements  HasMedia
{
    use InteractsWithMedia,HasFactory;
    
    protected $table = 'service_proofs';
    protected $fillable = [
        'title', 'description', 'service_id','booking_id' ,'user_id'
    ];

    protected $casts = [
        'service_id'    => 'integer',
        'booking_id'    => 'integer',
        'user_id'    => 'integer',
    ];
    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id')->withTrashed();
    }

    public function handyman(){
        return $this->belongsTo(User::class,'user_id', 'id')->withTrashed();
    }

    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id', 'id')->withTrashed();
    }
}
