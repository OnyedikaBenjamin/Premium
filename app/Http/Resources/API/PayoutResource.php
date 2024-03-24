<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class PayoutResource extends JsonResource
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
            'payment_method'    => $this->payment_method,
            'description'       => $this->description,
            'amount'            => $this->amount,
            'created_at'        => $this->created_at,
        ];
    }
}