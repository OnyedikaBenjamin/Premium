<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;
use App\Models\Booking;
use App\Models\ProviderPayout as ProviderPayoutModel;

class ProviderPayout extends FormRequest
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

         $provider_id = request()->provider_id;

        $payment_method = request()->payment_method;

        $provider = User::with('providertype')->find($provider_id);

        $bookings = Booking::where('provider_id',$provider_id)->whereNotNull('payment_id')->get();

        $providerEarning = ProviderPayoutModel::where('provider_id',$provider_id)->sum('amount');

        $provider_commission = optional($provider->providertype)->commission;

        $provider_type = optional($provider->providertype)->type;

        $booking_data = get_provider_commission($bookings);

        $provider_earning = calculate_commission($booking_data['total_amount'],$provider_commission,$provider_type,'provider', $providerEarning);
        if ( request()->is('api*')){
            $amount = request()->amount;
        
        }else{

            $amount = number_format((float)$provider_earning['number_format'],2,'.','');
        }
        switch ($method) {
            case 'POST':
 
               if($payment_method=='bank'){

                return [
                    'payment_method' => 'required',
                    'provider_id' => 'required',
                    'bank'=>'required',
                    'amount' => 'required|numeric|min:1|max:'.$amount,
                  ];

                }else{

                    return [
                        'payment_method' => 'required',
                        'provider_id' => 'required',
                        'amount' => 'required|numeric|min:1|max:'.$amount,
                    ];



                }
                
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
