<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;


class ProviderTaxResource extends JsonResource
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
            'id'                 => $this->id,
            'provider_id'        => $this->provider_id,
            'title'              => optional($this->taxes)->title,
            'type'              => optional($this->taxes)->type,
            'value'              => optional($this->taxes)->value,
        ];
    }
}
