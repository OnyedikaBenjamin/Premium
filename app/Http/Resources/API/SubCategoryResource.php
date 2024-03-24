<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $extention = imageExtention(getSingleMedia($this, 'subcategory_image',null));
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'status'           => $this->status,
            'description'      => $this->description,
            'is_featured'      => $this->is_featured,
            'color'            => $this->color,
            'category_id'      => $this->category_id,
            'category_image'=> getSingleMedia($this, 'subcategory_image',null),
            'category_extension' => $extention,
            'category_name' => optional($this->category)->name,
            'services' => $this->services->count(),
            'deleted_at' => $this->deleted_at
        ];
    }
}
