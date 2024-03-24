<?php

namespace App\Http\Resources\API;
use App\Models\Service;
use App\Models\PackageServiceMapping;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $service_id = PackageServiceMapping::where('service_package_id',$this->service_package_id)->pluck('service_id');
        return [
            'id'=> $this->id,
            'package_id' =>$this->service_package_id,
            'name'=> $this->name,
            'price'=> $this->price,
            'description'=> $this->description,
            'start_date'=> $this->start_at,
            'end_date'=> $this->end_at,
            'category_id'=> $this->category_id, // When package created based on Category wise//
            'subcategory_id'=> $this->subcategory_id, // When package created based on Category wise//
            'is_featured'=> $this->is_featured,
            'category_name'  => optional($this->category)->name,
            'subcategory_name'  => optional($this->subcategory)->name,
            'package_type' =>$this->package_type,
            // 'attchments' => getAttachments($this->package->getMedia('package_attachment')),
            // 'attchments_array' => getAttachmentArray($this->package->getMedia('package_attachment'),null),
            'services'    =>ServiceResource::collection(Service::whereIn('id',$service_id)->get())
        ];
    }
}
