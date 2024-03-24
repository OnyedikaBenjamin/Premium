<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProviderPayout;
use App\Models\HandymanPayout;
use App\Http\Resources\API\PayoutResource;

class PayoutController extends Controller
{
    public function providerPayoutList(Request $request)
    {
        $provider_id  = !empty($request->provider_id) ? $request->provider_id : auth()->user()->id;

        $payout = ProviderPayout::where('provider_id',$provider_id);
       
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $payout = new ProviderPayout();
                if( $request->has('user_id') && !empty($request->user_id)){
                    $payout = $payout->where('provider_id',$request->user_id);
    
                }
            }
    
        }
     
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $payout->count();
            }
        }

        $payout = $payout->orderBy('id','desc')->paginate($per_page);
        $items = PayoutResource::collection($payout);

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
    public function handymanPayoutList(Request $request){
        $handyman_id  = !empty($request->handyman_id) ? $request->handyman_id : auth()->user()->id;

        $payout = HandymanPayout::where('handyman_id',$handyman_id);
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $payout = new HandymanPayout();
                if( $request->has('user_id') && !empty($request->user_id)){
                    $payout = $payout->where('handyman_id',$request->user_id);

                }
            }
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $payout->count();
            }
        }

        $payout = $payout->orderBy('id','desc')->paginate($per_page);
        $items = PayoutResource::collection($payout);

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