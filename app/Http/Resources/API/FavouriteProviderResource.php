<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;


class FavouriteProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_id = !empty(request()->customer_id) ? request()->customer_id: auth()->user()->id;
        return [
            'id'            => $this->id,
            'provider_id'    => $this->provider_id,
            'is_favourite'  => 1,
            'profile_image'=> optional($this->provider)->login_type != null ? optional($this->provider)->social_image : getSingleMedia(optional($this->provider), 'profile_image',null),
            'display_name' => optional($this->provider)->display_name,
            'email' => optional($this->provider)->email,
            'contact_number' => optional($this->provider)->contact_number,
            'is_favourite'  => $this->where('user_id',$user_id)->first() ? 1 : 0,

        ];
    }
}
