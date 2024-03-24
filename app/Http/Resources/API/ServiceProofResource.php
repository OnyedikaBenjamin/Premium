<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProofResource extends JsonResource
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
            'title'             => $this->title,
            'description'       => $this->description,
            'service_id'        => $this->service_id,
            'booking_id'        => $this->booking_id,
            'user_id'           => $this->user_id,
            'handyman_name'     => optional($this->handyman)->display_name,
            'service_name'      => optional($this->service)->name,
            'attachments'        => getAttachments($this->getMedia('booking_attachment')),
        ];
    }
}