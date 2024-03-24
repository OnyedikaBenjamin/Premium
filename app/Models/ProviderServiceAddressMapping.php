<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderServiceAddressMapping extends Model
{
    use HasFactory;
    protected $table = 'provider_service_address_mappings';
    protected $fillable = [ 'service_id', 'provider_address_id' ];

    protected $casts = [
        'service_id'   => 'integer',
        'provider_address_id'   => 'integer',
    ];
    
    public function providerAddressMapping(){
        return $this->belongsTo(ProviderAddressMapping::class,'provider_address_id', 'id');
    }
}
