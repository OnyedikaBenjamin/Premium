<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia,SoftDeletes;
    protected $table = 'sub_categories';
    protected $fillable = [
        'name', 'description', 'is_featured', 'status' , 'category_id'
    ];

    protected $casts = [
        'status'    => 'integer',
        'is_featured'  => 'integer',
        'category_id'  => 'integer',
    ];
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id')->withTrashed();
    }
    public function services(){
        return $this->hasMany(Service::class, 'subcategory_id','id');
    }
    public function scopeList($query)
    {
        return $query->orderBy('deleted_at', 'asc');
    }
}
