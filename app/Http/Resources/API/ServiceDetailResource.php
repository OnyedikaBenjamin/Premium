<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ServicePackage;
class ServiceDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_id = request()->customer_id;
        $image = getSingleMedia($this,'service_attachment', null);
        $file_extention = config('constant.IMAGE_EXTENTIONS');
        $extention = in_array(strtolower(imageExtention($image)),$file_extention);
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'category_id'   => $this->category_id,
            'subcategory_id'   => $this->subcategory_id,
            'provider_id'   => $this->provider_id,
            'price'         => $this->price,
            'price_format'  => getPriceFormat($this->price),
            'type'          => $this->type,
            'discount'      => $this->discount,
            'duration'      => $this->duration,
            'status'        => $this->status,
            'description'   => $this->description,
            'is_featured'   => $this->is_featured,
            'provider_name' => optional($this->providers)->name,
            'category_name'  => optional($this->category)->name,
            'subcategory_name'  => optional($this->subcategory)->name,
            'attchments' => getAttachments($this->getMedia('service_attachment'),null),
            'attchments_array' => getAttachmentArray($this->getMedia('service_attachment'),null),
            'total_review'  => $this->serviceRating->count('id'),
            'total_rating'  => count($this->serviceRating) > 0 ? (float) number_format(max($this->serviceRating->avg('rating'),0), 2) : 0,
            'is_favourite'  => $this->getUserFavouriteService->where('user_id',$user_id)->first() ? 1 : 0,
            'service_address_mapping' => $this->providerServiceAddress,
            'attchment_extension' => $extention,
            'deleted_at' => $this->deleted_at,
            'is_slot'           => $this->is_slot,
            'slots'              => getServiceTimeSlot($this->provider_id ),
            'servicePackage'    => ServicePackageResource::collection(ServicePackage::whereIn('id',$this->servicePackage->pluck('service_package_id'))->where('status',1)->get()),
            'is_enable_advance_payment' => $this->is_enable_advance_payment,
            'advance_payment_amount' => $this->advance_payment_amount== null ? 0:(double) $this->advance_payment_amount,
        ];
    }
}
