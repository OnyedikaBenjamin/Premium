<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderAddressMappingResource extends JsonResource
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
            'provider_id'   => $this->provider_id,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'status'        => $this->status,
            'address'       => $this->address,
            'provider_name' => optional($this->providers)->display_name,
            'deleted_at'       => $this->deleted_at,
        ];
    }
}