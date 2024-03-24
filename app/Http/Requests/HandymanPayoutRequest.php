<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class HandymanPayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = request()->method();

        $handyman_id = request()->handyman_id;

        $handymanData =  User::where('id',$handyman_id)->first();
        $handymantype_id = !empty($handymanData->handymantype_id) ? $handymanData->handymantype_id : 1;

        $handyman_bookings = BookingHandymanMapping::with('bookings')->where('handyman_id',$id)->whereHas('bookings',function ($q){
            $q->whereNotNull('payment_id');
        })->get();

        $providerEarning = HandymanPayout::where('handyman_id',$handyman_id)->sum('amount');

        $get_commission = HandymanType::where('id',$handymantype_id)->first();

        $commission = $get_commission->commission;

        $all_booking_total = $handyman_bookings->map(function ($booking) {
            return optional($booking->bookings)->total_amount;
        })->toArray();

        $total = array_reduce($all_booking_total, function ($value1, $value2) {
            return $value1 + $value2;
        }, 0);
        
        $earning =   ($commission * count($handyman_bookings));
        if($get_commission->type === 'percent'){
            $earning =  ($total) * $commission / 100;
        }

        $amount = $earning;

        switch ($method) {
            case 'POST':
                return [
                    'payment_method' => 'required',
                    'handyman_id' => 'required',
                    'amount' => 'required|numeric|min:1|max:'.$amount,
                ];
                break;
            case 'DELETE':
                return [];
                break;
            default:
                return [];
                break;
        }
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ( request()->is('api*')){
            $data = [
                'status' => 'false',
                'message' => $validator->errors()->first(),
                'all_message' =>  $validator->errors()
            ];

            throw new HttpResponseException(response()->json($data,422));
        }

        throw new HttpResponseException(redirect()->back()->withInput()->with('errors', $validator->errors()));
    }
}
