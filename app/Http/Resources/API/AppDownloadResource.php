<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class AppDownloadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'          => $this->title,
            'description'        => $this->description,
            'playstore_url'   => $this->playstore_url,
            'appstore_url'   => $this->appstore_url,
            'provider_playstore_url'         => $this->provider_playstore_url,
            'provider_appstore_url'        => $this->provider_appstore_url,
            'app_image'=> getSingleMedia($this, 'app_image'),
            'app_image_full'=> getSingleMedia($this, 'app_image_full'),
        ];
    }
}
