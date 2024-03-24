<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'code'          => $this->code,
            'discount_type' => $this->discount_type,
            'discount'      => $this->discount,
            'expire_date'   => $this->expire_date,
            'status'        => $this->status,
            'type'          => $this->type,
            'deleted_at'        => $this->deleted_at,
        ];
    }
}
