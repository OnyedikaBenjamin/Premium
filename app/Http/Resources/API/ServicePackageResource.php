<?php

namespace App\Http\Resources\API;
use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePackageResource extends JsonResource
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
            'id'=> $this->id,
            'name'=> $this->name,
            'provider_id' => $this->provider_id,
            'price'=> $this->price,
            'status'=> $this->status,
            'description'=> $this->description,
            'start_date'=> $this->start_at,
            'end_date'=> $this->end_at,
            'category_id'=> $this->category_id, // When package created based on Category wise//
            'subcategory_id'=> $this->subcategory_id, // When package created based on Category wise//
            'is_featured'=> $this->is_featured,
            'services'=>  ServiceResource::collection(Service::whereIn('id',$this->packageServices->pluck('service_id'))->get()),
            'attchments' => getAttachments($this->getMedia('package_attachment')),
            'attchments_array' => getAttachmentArray($this->getMedia('package_attachment'),null),
            'category_name'  => optional($this->category)->name,
            'subcategory_name'  => optional($this->subcategory)->name,
            'package_type' =>$this->package_type
        ];
    }
}
