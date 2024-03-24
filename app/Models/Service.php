<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model implements  HasMedia
{
    use InteractsWithMedia,HasFactory,SoftDeletes;
    
    protected $table = 'services';
    protected $fillable = [
        'name', 'category_id', 'provider_id' , 'type' , 'is_slot','discount' , 'duration' ,'description', 
        'is_featured', 'status' , 'price' , 'added_by','subcategory_id','service_type',
        'is_enable_advance_payment','advance_payment_amount'
    ];

    protected $casts = [
        'category_id'               => 'integer',
        'subcategory_id'               => 'integer',
        'provider_id'               => 'integer',
        'price'                     => 'double',
        'discount'                  => 'double',
        'status'                    => 'integer',
        'is_featured'               => 'integer',
        'added_by'                  => 'integer',
        'is_slot'                   => 'integer',
        'is_enable_advance_payment' => 'integer',
        'advance_payment_amount'    => 'double',
    ];

    public function providers(){
        return $this->belongsTo('App\Models\User','provider_id','id')->withTrashed();
    }


    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id')->withTrashed();
    }
    public function subcategory(){
        return $this->belongsTo('App\Models\SubCategory','subcategory_id','id')->withTrashed();
    }
    public function serviceRating(){
        return $this->hasMany(BookingRating::class, 'service_id','id')->orderBy('created_at','desc');
    }
    public function serviceBooking(){
        return $this->hasMany(Booking::class, 'service_id','id');
    }
    public function serviceCoupons(){
        return $this->hasMany(CouponServiceMapping::class, 'service_id','id');
    }

    public function getUserFavouriteService(){
        return $this->hasMany(UserFavouriteService::class, 'service_id','id');
    }

    public function providerAddress(){
        return $this->hasMany(ProviderAddressMapping::class, 'provider_id','id');
    }

    public function providerServiceAddress(){
        return $this->hasMany(ProviderServiceAddressMapping::class, 'service_id','id')->with('providerAddressMapping');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($row) {
            $row->serviceBooking()->delete();
            $row->serviceCoupons()->delete();
            $row->serviceRating()->delete();
            $row->getUserFavouriteService()->delete();

            if($row->forceDeleting === true)
            {
                $row->serviceRating()->forceDelete();
                $row->serviceCoupons()->forceDelete();
                $row->serviceBooking()->forceDelete();
                $row->getUserFavouriteService()->forceDelete();
            }
        });

        static::restoring(function($row) {
            $row->serviceRating()->withTrashed()->restore();
            $row->serviceCoupons()->withTrashed()->restore();
            $row->serviceBooking()->withTrashed()->restore();
            $row->getUserFavouriteService()->withTrashed()->restore();
        });
    }
    public function scopeMyService($query)
    {
        if(auth()->user()->hasRole('admin')) {

            return $query->where('service_type', 'service')->withTrashed();
        }

        if(auth()->user()->hasRole('provider')) {
            return $query->where('provider_id', \Auth::id());
        }

        return $query;
    }
    
    public function scopeLocationService($query, $latitude = '', $longitude = '', $radius = 50, $unit = 'km'){
        if(default_earning_type() === 'subscription'){
            $provider = User::where('user_type','provider')->where('status',1)->where('is_subscribe',1)->pluck('id');
        }else{
            $provider = User::where('user_type','provider')->where('status',1)->pluck('id');
        }
        $unit_value = countUnitvalue($unit);
        $near_location_id = ProviderAddressMapping::selectRaw("id, provider_id, address, latitude, longitude,
                ( $unit_value * acos( cos( radians($latitude) ) *
                cos( radians( latitude ) )
                * cos( radians( longitude ) - radians($longitude)
                ) + sin( radians($latitude) ) *
                sin( radians( latitude ) ) )
                ) AS distance")
        ->where('status',1)
        ->whereIn('provider_id',$provider)
        ->having("distance", "<=", $radius)
        ->orderBy("distance",'asc')
        ->get()->pluck('id');
        return $near_location_id;        
    }
    public function scopeList($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
    public function servicePackage(){
        return $this->hasMany(PackageServiceMapping::class, 'service_id','id');
    }
    public function postJobService(){
        return $this->hasMany(PostJobServiceMapping::class, 'service_id', 'id');
    }
}
