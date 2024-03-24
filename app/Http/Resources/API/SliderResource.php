<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'type'          => $this->type,
            'type_id'       => $this->type_id,
            'status'        => $this->status,
            'description'   => $this->description,
            'service_name'  => optional($this->service)->name,
            'slider_image'  => getSingleMedia($this, 'slider_image',null),
        ];
    }
}
