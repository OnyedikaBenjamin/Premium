<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Service;
use App\Models\PostJobBid;


class PostJobRequestDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      
        $user = auth()->user();
        $can_bid = null;
        if($user->hasRole('provider')){
          $can_bid = true;
          $count = PostJobBid::where('provider_id',$user->id)->where('post_request_id',$this->id)->get();
          if(count($count) > 0){
            $can_bid = false;
          }
        }
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'reason'            => $this->reason,
            'price'             => $this->price,
            'provider_id'       => $this->provider_id,
            'customer_id'       => $this->customer_id,
            'customer_name'       => optional($this->customer)->display_name,
            'customer_profile'       => optional($this->customer)->login_type != null ? optional($this->customer)->social_image : getSingleMedia($this->customer, 'profile_image',null),
            'status'            => $this->status,
            'can_bid'           =>  $can_bid,
            'service'           => ServiceResource::collection(Service::whereIn('id',$this->postServiceMapping->pluck('service_id'))->get()),
            'job_price'             => $this->job_price,
        ];
    }
}