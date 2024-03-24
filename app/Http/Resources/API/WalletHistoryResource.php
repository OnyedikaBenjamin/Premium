<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletHistoryResource extends JsonResource
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
            'id'                => $this->id,
            'datetime'           => $this->datetime,
            'activity_type'             => $this->activity_type,
            'activity_message'        => $this->activity_message,
            'activity_data'            => $this->activity_data,
            
        ];
    }
}
