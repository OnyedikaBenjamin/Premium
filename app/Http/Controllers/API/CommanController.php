<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\Bank;
use App\Models\ProviderTaxMapping;
use App\Models\ProviderServiceAddressMapping;
use App\Http\Resources\API\ProviderTaxResource;
use App\Models\Service;
use App\Models\ProviderType;
use App\Models\HandymanType;
use App\Models\CouponServiceMapping;
use App\Models\Coupon;
use App\Models\Booking;
use App\Models\Tax;
use App\Models\AppSetting;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\TypeResource;
use App\Http\Resources\API\BankResource;
use App\Http\Resources\API\CouponResource;
use App\Http\Resources\API\TaxResource;
use PDF; 


class CommanController extends Controller
{
    public function getCountryList(Request $request)
    {
        $list = Country::get();

        return response()->json( $list );
    }

    public function getStateList(Request $request)
    {
        $list = State::where('country_id',$request->country_id)->get();

        return response()->json( $list );
    }

    public function getCityList(Request $request)
    {
        $list = City::where('state_id',$request->state_id)->get();

        return response()->json( $list );
    }
    public function getProviderTax(Request $request){
       
        $provider_id  = !empty($request->provider_id) ? $request->provider_id : auth()->user()->id;
        $taxes = ProviderTaxMapping::with('taxes');
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $taxes = $taxes;
            }
        }else{
            $taxes = $taxes->where('provider_id',$provider_id)->whereHas('taxes', function ($a)  {
                $a->where('status', 1);
            });
        }
      
       
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $taxes->count();
            }
        }
        $taxes = $taxes->orderBy('created_at','desc')->paginate($per_page);
        $items = ProviderTaxResource::collection($taxes);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
    public function getSearchList(Request $request){
        $service = Service::where('status',1)->where('service_type','service')->with(['providers','category','serviceRating'])->orderBy('created_at','desc');
        if($request->has('provider_id') && $request->provider_id != '' ){
            $service->whereIn('provider_id',explode(',',$request->provider_id));    
        }
        if($request->has('category_id') && $request->category_id != ''){
            $service->whereIn('category_id',explode(',',$request->category_id));
        }
        if($request->has('subcategory_id') && $request->subcategory_id != ''){
            $service->whereIn('subcategory_id',explode(',',$request->subcategory_id));
        }
        if($request->has('is_price_min') && $request->is_price_min != '' || $request->has('is_price_max') && $request->is_price_max != ''){
            $service->whereBetween('price', [$request->is_price_min, $request->is_price_max]); 
        }
        if($request->has('search')){
            $service->where('name','like',"%{$request->search}%");
        }
        if($request->has('is_featured')){
            $service->where('is_featured',$request->is_featured);
        }
        if($request->has('type')){
            $service->where('type',$request->type);
        }
        if($request->has('provider_id') && $request->provider_id != '' ){
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1);
            });
        }else{
            if(default_earning_type() === 'subscription'){
                $service->whereHas('providers', function ($a) use ($request) {
                    $a->where('status', 1)->where('is_subscribe',1);
                });
            }
        }
        if ($request->has('latitude') && !empty($request->latitude) && $request->has('longitude') && !empty($request->longitude)) {
            $get_distance = getSettingKeyValue('DISTANCE','DISTANCE_RADIOUS');
            $get_unit = getSettingKeyValue('DISTANCE','DISTANCE_TYPE');
            
            $locations = $service->locationService($request->latitude,$request->longitude,$get_distance,$get_unit);
            $service_in_location = ProviderServiceAddressMapping::whereIn('provider_address_id',$locations)->get()->pluck('service_id');
            $service->with('providerServiceAddress')->whereIn('id',$service_in_location);
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $service->count();
            }
        }
        $service = $service->where('status',1)->paginate($per_page);
        if($request->has('is_rating')){
            $service = $service->filter(function($data)  use($request) {
                $rating_array = explode(" ",$request->is_rating);
                return $data->serviceRating->avg('rating'); 
            });
            $service->whereBetween('rating', [1, 2]);
        }
        
        $items = ServiceResource::collection($service);
        $userservices  = null;
        if($request->customer_id != null){
            $user_service = Service::where('status',1)->where('added_by',$request->customer_id)->get();
            $userservices = ServiceResource::collection($user_service);
        }
        $response = [
            'data' => $items,
            'max'=> $service->max('price'),
            'min'=> $service->min('price'),
            'userservices' => $userservices
        ];
        
        return comman_custom_response($response);
    }

    public function getTypeList(Request $request){
        $user_type  = !empty($request->type) ? $request->type : '';
        if($user_type === 'provider'){
            $typeData = ProviderType::where('status',1);
        }else{
            $typeData = HandymanType::where('status',1);
        }
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                if($user_type === 'provider'){
                    $typeData = ProviderType::withTrashed();
                }else{
                    $typeData = HandymanType::withTrashed();
                }
            }
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $taxes->count();
            }
        }
        $typeData = $typeData->orderBy('id','desc')->paginate($per_page);
        $items = TypeResource::collection($typeData);
        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
    public function getCouponList(Request $request){
        $coupondata = Coupon::withTrashed();
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $taxes->count();
            }
        }
        $coupondata = $coupondata->orderBy('id','desc')->paginate($per_page);
        $items = CouponResource::collection($coupondata);
        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
    public function getCouponService(Request $request){
        $servicedata = CouponServiceMapping::where('coupon_id',$request->coupon_id)->withTrashed();
        $service_id = $servicedata->pluck('service_id');
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $taxes->count();
            }
        }
        $service = Service::whereIn('id',$service_id)->orderBy('id','desc')->paginate($per_page);
        $items = ServiceResource::collection($service);
        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($items);
    }
    public function downloadInvoice(Request $request){
        $email = $request->email;
        $booking_id = $request->booking_id;

        $bookingdata = Booking::with('handymanAdded', 'payment', 'bookingExtraCharge')->where('id',$booking_id)->first();

        $emailData['email'] = $request->email;
        $emailData['title'] = env('APP_NAME');
        $emailData['body'] = __('messages.invoice_mail_body',['booking_id'=> $booking_id]);
        $data =AppSetting::first();
        $pdf = PDF::loadView('booking.invoice',['bookingdata'=>$bookingdata ,'data'=> $data]);
        try {
            \Mail::send('booking.invoice_email', $emailData, function($message)use($data, $pdf,$emailData,$booking_id) {
                $message->to($emailData['email'])
                        ->subject($emailData['title'])
                        ->attachData($pdf->output(), 'invoice_'.$booking_id.'.pdf');
            });
      
            $messagedata = __('messages.send_invoice');
            return comman_message_response($messagedata);
        } catch (\Throwable $th) {
            $messagedata = __('messages.something_wrong');
            return comman_message_response($messagedata);
        }
        
    }
    public function getBankList(Request $request){
        $user_id = $request->user_id;
        $banks = Bank::where('provider_id',$user_id)->where('status',1);
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $banks->count();
            }
        }

        $banks = $banks->paginate($per_page);
        $items = BankResource::collection($banks);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
    public function getTaxList(Request $request){
       
        $taxes = new Tax();
       
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $taxes->count();
            }
        }
        $taxes = $taxes->orderBy('created_at','desc')->paginate($per_page);
        $items = TaxResource::collection($taxes);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
}