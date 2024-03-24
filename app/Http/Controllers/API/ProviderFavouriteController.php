<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFavouriteProvider;
use App\Http\Resources\API\FavouriteProviderResource;

class ProviderFavouriteController extends Controller
{
  
    public function saveFavouriteProvider(Request $request)
    {
        $user_favourite = $request->all();

        $result = UserFavouriteProvider::updateOrCreate(['id' => $request->id], $user_favourite);

        $message = __('messages.update_form',[ 'form' => __('messages.favourite') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.favourite') ] );
		}

        return comman_message_response($message);
    }

    public function deleteFavouriteProvider(Request $request)
    {
     
        $provider_fav = UserFavouriteProvider::where('user_id',$request->user_id)->where('provider_id',$request->provider_id)->first();
        if(!empty($provider_fav)){
            $provider_fav->delete();
        }
        
        $message = __('messages.delete_form',[ 'form' => __('messages.favourite') ] );

        return comman_message_response($message);
    }

    public function getUserFavouriteProvider(Request $request)
    {
        $user = auth()->user();

        $favourite = UserFavouriteProvider::where('user_id',$user->id);

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

        $items = FavouriteProviderResource::collection($favourite);

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