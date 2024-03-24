<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFaq extends Model
{
    use HasFactory;
    protected $table = 'service_faqs';
    protected $fillable = [
        'title', 'description', 'service_id', 'status'
    ];
    protected $casts = [
        'service_id'    => 'integer',
        'status'    => 'integer',
    ];
    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id');
    }
}
