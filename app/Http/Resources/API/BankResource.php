<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
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
            'provider_id'          => $this->provider_id,
            'bank_name'        => $this->bank_name,
            'branch_name'   => $this->branch_name,
            'account_no'   => $this->account_no,
            'ifsc_no'         => $this->ifsc_no,
            'mobile_no'         => $this->mobile_no,
            'aadhar_no'         => $this->aadhar_no,
            'pan_no'         => $this->pan_no,
        
        ];
    }
}
