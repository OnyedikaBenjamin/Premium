<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model implements  HasMedia
{
    use InteractsWithMedia, HasFactory, SoftDeletes;
    protected $table = 'sliders';
    protected $fillable = [
        'title', 'description', 'type', 'type_id', 'status'
    ];

    protected $casts = [
        'status'    => 'integer',
        'type_id'    => 'integer',

    ];

    public function service(){
        return $this->belongsTo(Service::class,'type_id','id');
    }
    public function scopeList($query)
    {
        return $query->orderBy('deleted_at', 'asc');
    }
}
