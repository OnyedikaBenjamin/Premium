<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderDocument extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,SoftDeletes;
    protected $table = 'provider_documents';
    protected $fillable = [
       'provider_id','document_id','is_verified'
    ];

    protected $casts = [
        'provider_id'   => 'integer',
        'document_id'   => 'integer',
        'is_verified'   => 'integer',
    ];

    public function providers(){
        return $this->belongsTo('App\Models\User','provider_id','id')->withTrashed();
    }   
    public function document(){
        return $this->belongsTo('App\Models\Documents','document_id','id')->withTrashed();
    }
    public function scopeMyDocument($query){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            $query =  $query;
        }

        if($user->hasRole('provider')) {
            $query = $query->where('provider_id', $user->id);
        }

        return  $query->whereHas('document',function ($q) {
            $q->where('status',1);
        });
    }
}
