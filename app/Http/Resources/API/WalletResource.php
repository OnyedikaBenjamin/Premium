<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'title'         => $this->title,
            'user_id'       => $this->user_id,
            'name'          => optional($this->providers)->display_name,
            'amount'        => $this->amount,
            'status'        => $this->status,
            
        ];
    }
}
