<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentHistoryResource extends JsonResource
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
            'payment_id'          => $this->payment_id,
            'booking_id'   => $this->booking_id,
            'action'   => $this->action,
            'text'   => $this->text,
            'type'   => $this->type,
            'status'   => $this->status,
            'sender_id'=> $this->sender_id,
            'receiver_id'        => $this->receiver_id,
            'datetime' => $this->datetime,
            'total_amount' =>  $this->total_amount,
            'txn_id' =>  $this->txn_id,
            'parent_id' =>  $this->parent_id,
            'other_transaction_detail' =>  $this->other_transaction_detail,
        ];
    }
}
