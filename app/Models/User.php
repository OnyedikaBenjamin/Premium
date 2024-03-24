<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, HasRoles, InteractsWithMedia,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 
        'password', 'user_type', 'address', 'contact_number', 'email_verified_at', 'remember_token',
        'handymantype_id',
        'player_id','country_id', 'state_id',  'city_id' , 'address', 'provider_id' , 'status', 
        'display_name', 'providertype_id' , 'is_featured' , 'time_zone' ,'last_notification_seen' , 
        'login_type','service_address_id' , 'uid','is_subscribe',
        'social_image','is_available','designation','last_online_time',
        'known_languages','skills','description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'country_id'        => 'integer',
        'state_id'          => 'integer',
        'city_id'           => 'integer',
        'is_featured'       => 'integer',
        'providertype_id'   => 'integer',
        'provider_id'       => 'integer',
        'service_address_id' => 'integer',
        'status'            => 'integer',
        'handymantype_id'            => 'integer',
        'service_address_id'            => 'integer',
        'is_subscribe'            => 'integer',
        'is_available'            => 'integer',
        'slots_for_all_services' => 'integer'
    ];

    protected static function boot(){
        parent::boot();

        /* static::created(function($user) {
            $user->assignRole('user');
        }); */
        static::deleted(function ($row) {
            switch ($row->user_type) {
                case 'provider':
                    if($row->forceDeleting === true){
                        $row->providerService()->forceDelete();
                        $row->providerBooking()->forceDelete();
                    }else{
                        $row->providerService()->delete();
                        $row->providerBooking()->delete();
                    }
                    break;

                case 'handyman':
                    if($row->forceDeleting === true){
                        $row->handyman()->forceDelete();
                    }else{
                        $row->handyman()->delete();
                    }
                    break;

                case 'customer':
                    if($row->forceDeleting === true){
                        $row->booking()->forceDelete();
                        $row->payment()->forceDelete();
                    }else{
                        $row->booking()->delete();
                        $row->payment()->delete();
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        });
        static::restoring(function($row) {
            switch ($row->user_type) {
                case 'provider':
                    $row->providerService()->withTrashed()->restore();
                    $row->providerBooking()->withTrashed()->restore();
                    break;

                case 'handyman':
                   $row->handyman()->withTrashed()->restore();
                    break;

                case 'customer':
                   $row->booking()->withTrashed()->restore();
                   $row->payment()->withTrashed()->restore();
                    break;
                
                default:
                    # code...
                    break;
            }
        });    
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id','id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id','id');
    }

    public function providertype(){
        return $this->belongsTo(ProviderType::class, 'providertype_id','id');
    }

    public function providers(){
        return $this->belongsTo(User::class, 'provider_id','id');
    }

    public function handyman(){
        return $this->hasMany(BookingHandymanMapping::class, 'handyman_id','id');
    }

    public function booking(){
        return $this->hasMany(Booking::class, 'customer_id','id');
    }

    public function payment(){
        return $this->hasMany(Payment::class, 'customer_id','id');
    }

    public function routeNotificationForOneSignal(){
        return $this->player_id;
    }

    protected function getUserByKeyValue($key,$value){
        return $this->where($key, $value)->first();
    }
    public function providerTaxMapping(){
        return $this->hasMany(ProviderTaxMapping::class, 'provider_id','id');
    }
    public function providerTaxMappingData(){
        return $this->hasMany(ProviderTaxMapping::class, 'provider_id','id')->with('taxes');
    }

    public function scopeMyUsers($query,$type=''){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            if($type === 'get_provider'){
                $query->where('user_type', 'provider')->where('status',1);
            }
            if($type === 'get_customer'){
                $query->where('user_type', 'user');
            }
            return $query;
        }
        if($user->hasRole('provider')) {
            return $query->where('user_type', 'handyman')->where('provider_id',$user->id);
        }
    }
    public function providerService(){
        return $this->hasMany(Service::class, 'provider_id','id');
    }
    public function providerHandyman(){
        return $this->hasMany(User::class, 'provider_id','id');
    }
    public function getServiceRating(){
        return $this->hasManyThrough(
            BookingRating::class,
            Service::class,
            'provider_id', // services
            'service_id', // booking rating
            'id', // users
            'id' // services
        );
    }

    public function providerBooking(){
        return $this->hasMany(Booking::class, 'provider_id','id');
    }
    public function providerPendingBooking(){
        return $this->hasMany(Booking::class, 'provider_id','id')->whereNull('payment_id');
    }
    public function handymanPendingBooking(){
        return $this->hasMany(BookingHandymanMapping::class, 'handyman_id','id')->whereHas('bookings', function($q){
            $q->whereNull('payment_id');
        });
    }
    public function handymanAddressMapping(){
        return $this->belongsTo(ProviderAddressMapping::class, 'service_address_id','id');
    }

    public function handymanRating(){
        return $this->hasMany(HandymanRating::class, 'handyman_id','id');
    }

    public function providerDocument(){
        return $this->hasMany(ProviderDocument::class, 'provider_id','id');
    }
    public function handymantype(){
        return $this->belongsTo(HandymanType::class, 'handymantype_id','id');
    }
    public function subscriptionPackage(){
        return $this->hasOne(ProviderSubscription::class, 'user_id','id')->where('status',config('constant.SUBSCRIPTION_STATUS.ACTIVE'));
    }
    public function providerbank(){
        return $this->hasMany(Bank::class, 'provider_id','id');
    }
    public function playerids(){
        return $this->hasMany(UserPlayerIds::class, 'user_id','id');
    }
    public function scopeList($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
}
