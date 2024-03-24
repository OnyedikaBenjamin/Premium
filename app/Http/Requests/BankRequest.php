<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
class BankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->id;

        return [
            'provider_id'            => 'required',
            'bank_name'              => 'required',
            'account_no'             => 'required',
            'branch_name'            => 'required',
            'ifsc_no'                => 'required|unique:banks,ifsc_no,'.$id,
            'mobile_no'              => 'required|unique:banks,mobile_no,'.$id,
            'aadhar_no'              => 'required|unique:banks,aadhar_no,'.$id,
            'pan_no'                 => 'required|unique:banks,pan_no,'.$id,
            'status'                 => 'required',
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
