<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AppDownload extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = ['title', 'description','playstore_url','appstore_url','provider_playstore_url','provider_appstore_url'];
}
