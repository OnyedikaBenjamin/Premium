<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingChargesResource extends JsonResource
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
            'id'               => $this->id,
            'booking_id'             => $this->booking_id,
            'title'       => $this->title,
            'price'           => $this->price,
            'qty'             => $this->qty
        ];
    }
}
