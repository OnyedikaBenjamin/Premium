<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPackageMapping extends Model
{
    use HasFactory;
    protected $table = 'booking_package_mappings';
    protected $fillable = [
        'booking_id', 'service_package_id', 'provider_id', 'name' , 'description','start_at',
        'end_at','is_featured','category_id','subcategory_id','package_type','price'
    ];
    protected $casts = [
        'provider_id'    => 'integer',
        'booking_id'    => 'integer',
        'service_package_id'    => 'integer',
        'category_id'    => 'integer',
        'subcategory_id'    => 'integer',
        'status'    => 'integer',
        'price'  => 'double',
        'is_featured'  => 'integer',
    ];
    public function package(){
        return $this->belongsTo(ServicePackage::class, 'service_package_id','id');
    }
}
