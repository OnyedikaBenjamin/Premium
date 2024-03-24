<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,SoftDeletes;
    protected $table = 'banks';
    protected $fillable = [
        'provider_id', 'bank_name', 'branch_name','account_no','ifsc_no' , 'mobile_no','aadhar_no','pan_no','status','stripe_account'
    ];

    protected $casts = [
        'provider_id'    => 'integer',
        'status'    => 'integer',
    ];
    public function providers(){
        return $this->belongsTo('App\Models\User','provider_id','id')->withTrashed();
    }


}
