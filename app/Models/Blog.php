<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model implements  HasMedia
{
    use InteractsWithMedia,HasFactory,SoftDeletes;
    protected $table = 'blogs';
    protected $fillable = [
        'title', 'description', 'total_views' , 'author_id' , 'status','is_featured'
    ];

    protected $casts = [
        'total_views'         => 'double',
        'status'              => 'integer',
        'is_featured'         => 'integer',
    ];
    public function scopeMyBlog($query)
    {
        if(auth()->user()->hasRole('admin')) {
            return $query;
        }

        if(auth()->user()->hasRole('provider')) {
            return $query->where('author_id', \Auth::id());
        }

        return $query;
    }
    public function author(){
        return $this->belongsTo('App\Models\User','author_id','id')->withTrashed();
    }
}
