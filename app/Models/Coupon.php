<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupons';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code', 'discount_type', 'discount', 'expire_date', 'status','type'
    ];
    
    protected $casts = [
        'discount'  => 'double',
        'status'    => 'integer',
    ];

    protected static function boot(){
        parent::boot();
        static::deleted(function ($row) {
            $row->serviceAdded()->delete();
            if($row->forceDeleting === true)
            {
                $row->serviceAdded()->forceDelete();
            }
        });
        static::restoring(function($row) {
            $row->serviceAdded()->withTrashed()->restore();
        });
    }

    public function serviceAdded(){
        return $this->hasMany(CouponServiceMapping::class,'coupon_id','id');
    }

    public function getExpireDateAttribute($value) {
        if($value!=null)
            return $this->attributes['expire_date'] = Carbon::parse($value)->format('Y-m-d H:i');
    }
    public function scopeList($query)
    {
        return $query->orderBy('deleted_at', 'asc');
    }
}
