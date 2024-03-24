<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BookingStatus;
class BookingDetailResource extends JsonResource
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
            'address'            => $this->address,
            'customer_id'        => $this->customer_id,
            'service_id'         => $this->service_id,
            'provider_id'        => $this->provider_id,
            'quantity'           => $this->quantity,
            'price'              => optional($this->service)->price,
            'price_format'       => getPriceFormat(optional($this->service)->price),
            'type'               => optional($this->service)->type,
            'discount'           => optional($this->service)->discount,
            'status'             => $this->status,
            'status_label'       => BookingStatus::bookingStatus($this->status),
            'description'        => $this->description,
            'reason'             => $this->reason,
            'provider_name'      => optional($this->provider)->display_name,
            'customer_name'      => optional($this->customer)->display_name,
            'service_name'       => optional($this->service)->name,
            'payment_status'     => optional($this->payment)->payment_status,
            'payment_method'     => optional($this->payment)->payment_type,
            'total_review'       => $this->bookingRating->count('id'),
            'total_rating'       => count($this->bookingRating) > 0 ? (float) number_format(max($this->bookingRating->avg('rating'),0), 2) : 0,
            'date'               => $this->date,
            'start_at'           => $this->start_at,
            'end_at'             => $this->end_at,
            'duration_diff'      => $this->duration_diff,
            'payment_id'         => $this->payment_id,
            'booking_address_id' => $this->booking_address_id,
            'duration_diff_hour' => ($this->service->type === 'hourly') ? convertToHoursMins($this->duration_diff) : null,
            'total_amount'       => $this->total_amount,
            'amount'             => $this->amount,
            'taxes'              => json_decode($this->tax,true),
            'extra_charges'         => BookingChargesResource::collection($this->bookingExtraCharge),
            'booking_type'            => $this->type,
            'post_request_id'            => $this->post_request_id,
            'booking_slot' => $this->booking_slot,
            'booking_package'              => new BookingPackageResource($this->bookingPackage),
            'advance_paid_amount'  => $this->advance_paid_amount == null ? 0:(double) $this->advance_paid_amount,
            'advance_payment_amount' => optional($this->service)->advance_payment_amount == null ? 0:(bool) optional($this->service)->advance_payment_amount,
            'final_total_service_price' => $this->final_total_service_price,
            'final_total_tax'=> $this->final_total_tax,
            'final_sub_total'=> $this->final_sub_total,
            'final_discount_amount'=> $this->final_discount_amount,
            'final_coupon_discount_amount'=> $this->final_coupon_discount_amount,
            'txn_id' => optional($this->payment)->txn_id

        ];
    }
}
