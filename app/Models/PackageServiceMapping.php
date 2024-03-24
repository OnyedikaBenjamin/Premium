<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageServiceMapping extends Model
{
    use HasFactory;
    protected $table = 'package_service_mappings';
    protected $fillable = [
        'service_package_id', 'service_id',
    ];
    protected $casts = [
        'service_package_id'    => 'integer',
        'service_id'  => 'integer',
    ];
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }
}
