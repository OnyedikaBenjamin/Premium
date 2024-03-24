<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BookingStatus;
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $extraValue = 0;
        if($this->bookingExtraCharge->count() > 0){
            foreach($this->bookingExtraCharge as $chrage){
                $extraValue += $chrage->price * $chrage->qty;
            }
        }
     
        return [
            'id'                    => $this->id,
            'address'               => $this->address,
            'customer_id'           => $this->customer_id,
            'service_id'            => $this->service_id,
            'provider_id'           => $this->provider_id,
            'date'                  => $this->date,
            'price'                 => optional($this->service)->price,
            'type'                  => optional($this->service)->type,
            'discount'              => optional($this->service)->discount,
            'status'                => $this->status,
            'status_label'          => BookingStatus::bookingStatus($this->status),
            'description'           => $this->description,
            'provider_name'         => optional($this->provider)->display_name,
            'customer_name'         => optional($this->customer)->display_name,
            'service_name'          => optional($this->service)->name,
            'payment_id'            => $this->payment_id,
            'payment_status'        => optional($this->payment)->payment_status,
            'payment_method'        => optional($this->payment)->payment_type,
            'provider_name'         => optional($this->provider)->display_name,
            'customer_name'         => optional($this->customer)->display_name,
            'service_name'          => optional($this->service)->name,
            'handyman'              => isset($this->handymanAdded) ? $this->handymanAdded : [],
            'service_attchments'    => getAttachments(optional($this->service)->getMedia('service_attachment'),null),
            'duration_diff'         => $this->duration_diff,
            'booking_address_id'    => $this->booking_address_id,
            'duration_diff_hour'    => ($this->service->type === 'hourly') ? convertToHoursMins($this->duration_diff) : null,
            'taxes'                 => json_decode($this->tax,true),
            'quantity'              => $this->quantity,
            'coupon_data'           => isset($this->couponAdded) ? $this->couponAdded : null,
            'total_amount'          => $this->total_amount,
            'total_rating'          => (float) number_format(max(optional($this->service)->serviceRating->avg('rating'),0), 2),
            'amount'                => $this->amount,
            'extra_charges'         => BookingChargesResource::collection($this->bookingExtraCharge),
            'extra_charges_value'            => $extraValue,
            'booking_type'            => $this->type,
            'booking_slot' => $this->booking_slot,
            'total_review' => optional($this->service)->serviceRating->count(),
            'booking_package'              => new BookingPackageResource($this->bookingPackage),
            'advance_paid_amount'  => $this->advance_paid_amount == null ? 0:(double) $this->advance_paid_amount,
            'advance_payment_amount' => optional($this->service)->advance_payment_amount == null ? 0:(bool) optional($this->service)->advance_payment_amount,

        ];
    }
}
