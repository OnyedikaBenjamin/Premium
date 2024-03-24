<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PostJobRequest extends Model
{
    use HasFactory;
    protected $table = 'post_job_requests';
    protected $fillable = [
        'title', 'customer_id', 'status' ,'description','provider_id','reason','price','date','job_price'
    ];

    protected $casts = [
        'customer_id'  => 'integer',
        'provider_id'  => 'integer',
        'price' => 'double',
        'job_price' => 'double',
    ];
    public function postServiceMapping(){
        return $this->hasMany(PostJobServiceMapping::class, 'post_request_id','id');
    }
    public function scopeMyPostJob($query)
    {
        if(auth()->user()->hasRole('admin')) {
            return $query;
        }

        if(auth()->user()->hasRole('user')) {
            return $query->where('customer_id', \Auth::id());
        }
        if(auth()->user()->hasRole('provider')) {
            return $query;
        }
        return $query;
    }
    public function postBidList(){
        return $this->hasMany(PostJobBid::class, 'post_request_id','id');
    }
    public function provider(){
        return $this->belongsTo(User::class,'provider_id', 'id')->withTrashed();
    }
    public function customer(){
        return $this->belongsTo(User::class,'customer_id', 'id')->withTrashed();
    }
}
