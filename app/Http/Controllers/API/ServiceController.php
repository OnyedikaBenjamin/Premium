<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Coupon;
use App\Models\BookingRating;
use App\Models\UserFavouriteService;
use App\Models\CouponServiceMapping;
use App\Models\ServiceFaq;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\API\ServiceDetailResource;
use App\Http\Resources\API\BookingRatingResource;
use App\Http\Resources\API\CouponResource;
use App\Http\Resources\API\UserFavouriteResource;
use App\Http\Resources\API\ProviderTaxResource;
use App\Models\ProviderServiceAddressMapping;
use App\Models\ProviderTaxMapping;
use App\Models\Category;

class ServiceController extends Controller
{
    public function getServiceList(Request $request){

        $service = Service::where('service_type','service')->with(['providers','category','serviceRating'])->orderBy('created_at','desc');

        $category = Category::onlyTrashed()->get();
        $category = $category->pluck('id');
        $service = $service->whereNotIn('category_id',$category);
        
        
        if(auth()->user() !== null && auth()->user()->hasRole('admin')){
            $service = $service->withTrashed();
        }elseif(auth()->user() !== null && auth()->user()->hasRole('provider')){
            $service = $service;
        }else{
            $service =$service->where('status',1);
        }
        if($request->has('status') && isset($request->status)){
            $service->where('status',$request->status);
        }
        
        if($request->has('provider_id')){
            $service->where('provider_id',$request->provider_id);        
        }
        
        if($request->has('category_id')){
            $service->where('category_id',$request->category_id);
        }
        if($request->has('subcategory_id') && $request->subcategory_id != ''){
            $service->whereIn('subcategory_id',explode(',',$request->subcategory_id));
        }
        if($request->has('is_featured')){
            $service->where('is_featured',$request->is_featured);
        }
        if($request->has('is_discount')){
            $service->where('discount','>',0)->orderBy('discount','desc');
        }
        if($request->has('is_rating') && $request->is_rating != ''){
            $service->whereHas('serviceRating', function($q) use ($request) {
                $q->select('service_id',\DB::raw('round(AVG(rating),0) as total_rating'))->groupBy('service_id');
                return $q;
            });
        }
        if($request->has('is_price_min') && $request->is_price_min != '' || $request->has('is_price_max') && $request->is_price_max != ''){
            $service->whereBetween('price', [$request->is_price_min, $request->is_price_max]); 
        }
        if ($request->has('city_id')) {
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('city_id', $request->city_id);
            });
        }
        
        if($request->has('provider_id') && $request->provider_id != '' ){
            $service->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1);
            });
        }else{
            if(default_earning_type() === 'subscription'){
               if(auth()->user() !== null && !auth()->user()->hasRole('admin')){
                $service->whereHas('providers', function ($a) use ($request) {
                    $a->where('status', 1)->where('is_subscribe',1);
                });
               }
                
            }
        }
        if ($request->has('latitude') && !empty($request->latitude) && $request->has('longitude') && !empty($request->longitude)) {
            $get_distance = getSettingKeyValue('DISTANCE','DISTANCE_RADIOUS');
            $get_unit = getSettingKeyValue('DISTANCE','DISTANCE_TYPE');
            
            $locations = $service->locationService($request->latitude,$request->longitude,$get_distance,$get_unit);
            $service_in_location = ProviderServiceAddressMapping::whereIn('provider_address_id',$locations)->get()->pluck('service_id');
            $service->with('providerServiceAddress')->whereIn('id',$service_in_location);
        }

        if($request->has('search')){
            $service->where('name','like',"%{$request->search}%");
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

        if(auth()->user() !== null && auth()->user()->hasRole('admin')){

            $service = $service->orderBy('created_at','desc');
           
        }else{

            $service = $service->where('status',1)->orderBy('created_at','desc');

        }

        $service = $service->paginate($per_page);
     
        $items = ServiceResource::collection($service);

        $userservices  = null;
        if($request->customer_id != null){
            $user_service = Service::where('status',1)->where('added_by',$request->customer_id)->get();
            $userservices = ServiceResource::collection($user_service);
        }
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
            'user_services' => $userservices,
            'max'=> $service->max('price'),
            'min'=> $service->min('price'),
        ];
        
        return comman_custom_response($response);
    }

    public function getServiceDetail(Request $request){
        $id = $request->service_id;
       
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $service = Service::where('service_type','service')->withTrashed()->with('providers','category','serviceRating')->findorfail($id);
            }
            else{
                $service = Service::where('service_type','service')->with('providers','category','serviceRating')->findorfail($id);
            }
        }else{
            $service = Service::where('service_type','service')->where('status',1)->with('providers','category','serviceRating')->find($id);
        }
       
        if(empty($service)){
            $message = __('messages.record_not_found');
            return comman_message_response($message,406);   
        }


        $service_detail = new ServiceDetailResource($service);
        $related = $service->where('service_type','service')->where('category_id',$service->category_id);
         if(default_earning_type() === 'subscription'){
    
            $related->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1)->where('is_subscribe',1);
            });
        }else{
            $related->whereHas('providers', function ($a) use ($request) {
                $a->where('status', 1);
            });
        }
        $related = $related->get();
        $related_service = ServiceResource::collection($related);

        $rating_data = BookingRatingResource::collection($service_detail->serviceRating->take(5));
                
        $customer_reviews = [];
        if($request->customer_id != null){
            $customer_review = BookingRating::where('customer_id',$request->customer_id)->where('service_id',$id)->get();
            if (!empty($customer_review))
            {
                $customer_reviews = BookingRatingResource::collection($customer_review);
            }
        }
        
        $coupon = Coupon::with('serviceAdded')
                ->where('expire_date','>',date('Y-m-d H:i'))
                ->where('status',1)
                ->whereHas('serviceAdded',function($coupon) use($id){
                    $coupon->where('service_id', $id );
                })->get();
        $coupon_data = CouponResource::collection($coupon);
        $tax = ProviderTaxMapping::with('taxes')->where('provider_id',$service->provider_id)->get();
        $taxes = ProviderTaxResource::collection($tax);
        $servicefaq =  ServiceFaq::where('service_id',$id)->get();
        $response = [
            'service_detail'    => $service_detail,
            'provider'          => new UserResource(optional($service->providers)),
            'rating_data'       => $rating_data,
            'customer_review'   => $customer_reviews,
            'coupon_data'       => $coupon_data,
            'taxes'             => $taxes,
            'related_service'   => $related_service,
            'service_faq'        => $servicefaq
        ];

        return comman_custom_response($response);
    }

    public function getServiceRating(Request $request){

        $rating_data = BookingRating::where('service_id',$request->service_id);

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $rating_data->count();
            }
        }

        $rating_data = $rating_data->orderBy('id','desc')->paginate($per_page);
        $items = BookingRatingResource::collection($rating_data);

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
    public function saveFavouriteService(Request $request)
    {
        $user_favourite = $request->all();

        $result = UserFavouriteService::updateOrCreate(['id' => $request->id], $user_favourite);

        $message = __('messages.update_form',[ 'form' => __('messages.favourite') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.favourite') ] );
		}

        return comman_message_response($message);
    }

    public function deleteFavouriteService(Request $request)
    {
        
        $service_rating = UserFavouriteService::where('user_id',$request->user_id)->where('service_id',$request->service_id)->delete();
        
        $message = __('messages.delete_form',[ 'form' => __('messages.favourite') ] );

        return comman_message_response($message);
    }

    public function getUserFavouriteService(Request $request)
    {
        $user = auth()->user();

        $favourite = UserFavouriteService::where('user_id',$user->id);

        $per_page = config('constant.PER_PAGE_LIMIT');

        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $favourite->count();
            }
        }

        $favourite = $favourite->orderBy('created_at','desc')->paginate($per_page);

        $items = UserFavouriteResource::collection($favourite);

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
    public function getTopRatedService(){
        $rating_data = BookingRating::whereNotNull('review')->orderBy('rating','desc')->limit(5)->get();
        $items = BookingRatingResource::collection($rating_data);

        $response = [
            'data' => $items,
        ];
        
        return comman_custom_response($response);
    }
    public function serviceReviewsList(Request $request){
        $id = $request->service_id;
        $rating_data = BookingRating::where('service_id',$id);

        $per_page = config('constant.PER_PAGE_LIMIT');

        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $rating_data->count();
            }
        }

        $rating_data = $rating_data->orderBy('created_at','desc')->paginate($per_page);

        $items = BookingRatingResource::collection($rating_data);
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
    public function saveServiceCoupon(Request $request){
        $data = $request->all();

        $data['expire_date'] = isset($request->expire_date) ? date('Y-m-d H:i:s',strtotime($request->expire_date)) : date('Y-m-d H:i:s');
        $result = Coupon::updateOrCreate(['id' => $data['id'] ],$data);
        if($result){
            $service_data = [
                'coupon_id'   => $result->id,
                'service_id'  =>  $service
            ];
            CouponServiceMapping::Create($service_data);
        }
        $message = trans('messages.update_form',['form' => trans('messages.coupon')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.coupon')]);
        }
        return comman_message_response($message);
    }
}