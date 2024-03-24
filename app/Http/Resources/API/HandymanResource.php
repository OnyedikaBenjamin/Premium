<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\API\UserResource;
use App\Models\HandymanRating;
use App\Http\Resources\API\HandymanRatingResource;
class HandymanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $booking_id = request()->booking_id;
        $rating = null;
        if($booking_id != null){
            $rating = HandymanRating::where('booking_id',$booking_id)->where('handyman_id',$this->handyman->id)->first();
            if($rating != null)
            {
                $rating = new HandymanRatingResource($rating);
            }
        }
        $temp = new UserResource($this->handyman);
        
        $collection = collect($temp)->put('handyman_review', $rating);

        return $collection;
    }
}
