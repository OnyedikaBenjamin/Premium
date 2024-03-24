<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Booking extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'bookings';
    protected $fillable = [
        'customer_id', 
        'service_id',
        'post_request_id',
        'type', 
        'provider_id', 
        'date', 'start_at' , 
        'end_at' ,
        'amount' , 
        'discount','total_amount' ,
        'quantity', 
        'description' , 
        'coupon_id' , 
        'status' , 
        'payment_id' ,
        'reason' , 
        'address' ,
        'duration_diff' , 
        'booking_address_id',
        'tax',
        'booking_slot',
        'booking_day',
        'advance_paid_amount',
        'final_total_service_price',
        'final_total_tax',
        'final_sub_total',
        'final_discount_amount',
        'final_coupon_discount_amount'
    ];

    protected $casts = [
        'customer_id'   => 'integer',
        'service_id'    => 'integer',
        'provider_id'   => 'integer',
        'quantity'      => 'integer',
        'amount'        => 'double',
        'discount'      => 'double',
        'total_amount'  => 'double',
        'coupon_id'     => 'integer',
        'payment_id'    => 'integer',
        'booking_address_id' => 'integer',
        'advance_paid_amount' => 'double',
        'post_request_id' => 'integer',
        'final_total_service_price'=> 'double',
        'final_total_tax'=> 'double',
        'final_sub_total'=> 'double',
        'final_discount_amount'=> 'double',
        'final_coupon_discount_amount'=> 'double',
    ];
    public function customer(){
        return $this->belongsTo(User::class,'customer_id', 'id')->withTrashed();
    }

    public function provider(){
        return $this->belongsTo(User::class,'provider_id', 'id')->withTrashed();
    }

    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id')->withTrashed();
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id', 'id');
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'id', 'booking_id')->orderBy('id', 'desc')->withTrashed();
    }

    public function bookingRating(){
        return $this->hasMany(BookingRating::class, 'service_id','service_id')->with(['customer']);
    }

    public function couponAdded(){
        return $this->belongsTo(BookingCouponMapping::class,'id','booking_id');
    }

    public function handymanAdded(){
        return $this->hasMany(BookingHandymanMapping::class,'booking_id','id')->with(['handyman']);
    }
    
    public function bookingActivity(){
        return $this->hasMany(BookingActivity::class,'booking_id','id');
    }

    public function scopeMyBooking($query){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->where('provider_id', $user->id);
        }

        if($user->hasRole('user')) {
            return $query->where('customer_id', $user->id);
        }

        if($user->hasRole('handyman')) {
            return $query->whereHas('handymanAdded',function ($q) use($user){
                $q->where('handyman_id',$user->id);
            });
        }

        return $query;
    }

    public function categoryService(){
        return $this->belongsTo(Service::class,'service_id', 'id')->with('category');
    }

    public function addressAdded(){
        return $this->belongsTo(BookingAddressMapping::class,'id','booking_id');
    }
    public function bookingTaxMapping(){
        return $this->hasMany(BookingTaxMapping::class,'id','booking_id');
    }
    public function scopeShowServiceCount($query){
        $query->select(\DB::raw('DISTINCT service_id, COUNT(*) AS count_pid'))
              ->groupBy('service_id')
              ->orderBy('count_pid', 'desc');
        return $query->with('categoryService');
    }

    protected static function boot(){
        parent::boot();
        static::deleted(function ($row) {
            $row->couponAdded()->delete();
            $row->bookingActivity()->delete();
            $row->payment()->delete();
            $row->handymanAdded()->delete();
            $row->bookingRating()->delete();
            if($row->forceDeleting === true)
            {
                $row->couponAdded()->delete();
                $row->bookingActivity()->delete();
                $row->payment()->delete();
                $row->handymanAdded()->delete();
                $row->bookingRating()->delete();
            }
        });

        static::restoring(function($row) {
            $row->service()->withTrashed()->restore();
            $row->provider()->withTrashed()->restore();
            $row->customer()->withTrashed()->restore();
            $row->bookingActivity()->withTrashed()->restore(); 
            $row->couponAdded()->withTrashed()->restore();
            $row->payment()->withTrashed()->restore();
            $row->handymanAdded()->withTrashed()->restore();
            $row->bookingRating()->withTrashed()->restore();
        });    
    }

    public function handymanByAddress(){
        return $this->belongsTo(ProviderAddressMapping::class,'booking_address_id','id')->with(['handyman']);
    }
    public function providerAddress(){
        return $this->belongsTo(ProviderAddressMapping::class,'booking_address_id','id');
    }
    public function bookingExtraCharge(){
        return $this->hasMany(BookingExtraCharge::class, 'booking_id','id');
    }
    public function bookingPostJob(){
        return $this->belongsTo(PostJobRequest::class, 'post_request_id','id');
    }
    public function bookingPackage(){
        return $this->belongsTo(BookingPackageMapping::class, 'id','booking_id')->with('package');
    }
    public function scopeList($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    public function getHourlyPrice():float
    {
        $totalOneHourSeconds = 3600;
        $totalMinutes = 0;
      
        $perMinuteCharge = $this->amount / 60;
      
        if ($this->duration_diff <= $totalOneHourSeconds) {
          $totalMinutes = $totalOneHourSeconds / 60;
        } else {
          $totalMinutes = $this->duration_diff / 60;
        }
        return $totalMinutes * $perMinuteCharge;
    }
    public function getServiceTotalPrice(): float
    {
       $serviceTotalPrice = 0;
       
       if($this->service !== null && $this->service->type == 'hourly'){
        $serviceTotalPrice += $this->getHourlyPrice();
       }else{
        $serviceTotalPrice += ($this->amount) *  (!empty($this->quantity) ? $this->quantity : 1);

       }
       return $serviceTotalPrice;
    }
    public function getDiscountValue(): float
    {
        $discount = $this->bookingPackage == null && $this->discount != 0 ? (($this->getServiceTotalPrice()/ 100) * $this->discount) : 0;

        return $discount  ;
    }
    public function getCouponDiscountValue(): float
    {
        $couponAmount = 0.0;
        if ($this->couponAdded != null) {
          if ($this->couponAdded->discount_type == 'fixed') {
            $couponAmount = $this->couponAdded->discount;
          } else {
            $couponAmount = ($this->getServiceTotalPrice() * $this->couponAdded->discount) / 100;
          }
        }

        return $couponAmount;
    }
    public function getSubTotalValue():float
    {
        $subTotal = 0;
        $subTotal = $this->getServiceTotalPrice() - $this->getDiscountValue() - $this->getCouponDiscountValue();

        return $subTotal;
    }
    public function getExtraChargeValue(): float
    {
        $extraCharge = 0;
        if (!empty($this->bookingExtraCharge)) {
            foreach (json_decode($this->bookingExtraCharge,true) as $charge) {
                $extraCharge += $charge['price'] * $charge['qty'];
            }
        }

        return $extraCharge;
    }
    public function getTaxesValue(): float
    {
        $total = $this->getSubTotalValue() + $this->getExtraChargeValue();
        $taxValue = 0;
        if (!empty($this->tax)) {
            foreach (json_decode($this->tax,true) as $tax) {
                if ($tax['type'] == 'percent') {
                    $taxValue += ($total * $tax['value'] / 100);
                } else {
                    $taxValue += $tax['value'];
                }
            }
        }

        return $taxValue;
    }
    public function getTotalValue(): float
    {
       $grandTotalAmount =  $this->getSubTotalValue()  + $this->getTaxesValue() + $this->getExtraChargeValue();

       return $grandTotalAmount;
    }
}
