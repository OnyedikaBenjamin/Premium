<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'app_settings';

    protected $fillable = ['site_name', 'site_email', 'site_logo', 'site_favicon','site_description','site_copyright','facebook_url','twitter_url','linkedin_url' , 'language_option','inquriy_email','helpline_number','instagram_url','youtube_url','time_zone','earning_type'];

	public $timestamps = false;

	protected function getData()
    {
		$data=	self::get()->first();

		if($data == null){
			$data = new self;
		}
		
        $data->site_logo = getSingleMedia($data,'site_logo');
		$data->site_favicon = getSingleMedia($data,'site_favicon');

		return $data;
	}

	public function setLanguageOptionAttribute($value)
    {
        $this->attributes['language_option'] = isset($value) ? json_encode($value):null;
    }

    public function getLanguageOptionAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;

        if($val == null){
            $val = collect(languagesArray())->pluck('id')->toArray();
        }

        if(!in_array(ENV('DEFAULT_LANGUAGE'), $val)){
            array_push($val, ENV('DEFAULT_LANGUAGE'));
        }

        return $val;
    }
    public static function getAppSettings()
    {
        return Cache::rememberForever('app.settings.all', function () {
            return self::all();
        });
    }
    public static function flushCache()
    {
        Cache::forget('app.settings.all');
    }
    protected static function boot()
    {
        parent::boot();

        static::updated(function () {
            self::flushCache();
        });

        static::created(function () {
            self::flushCache();
        });

        static::deleted(function () {
            self::flushCache();
        });
    }
}