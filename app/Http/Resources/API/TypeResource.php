<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
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
            'name'             => $this->name,
            'commission'       => $this->commission,
            'status'           => $this->status,
            'type'             => $this->type,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'deleted_at'        => $this->deleted_at,
        ];
    }
}
