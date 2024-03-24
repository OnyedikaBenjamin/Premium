<?php

namespace App\Http\Resources\API;
use App\Http\Resources\API\BookingDetailResource;

use Illuminate\Http\Resources\Json\JsonResource;

class GetCashPaymentHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'service'       => $this->booking->service->name,
            'user'          => $this->booking->customer->display_name,
            'text'          => $this->text,
        ];
    }
}
