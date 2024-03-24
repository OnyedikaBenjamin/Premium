<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostRequestStatus;
use App\Models\PostJobRequest;
use App\Models\PostJobBid;
use App\Http\Resources\API\PostJobRequestResource;
use App\Http\Resources\API\PostJobBiderResource;
use App\Http\Resources\API\PostJobRequestDetailResource;

class PostJobRequestController extends Controller
{
  
    public function postRequestStatus(Request $request)
    {
        $post_job_status = PostRequestStatus::orderBy('sequence')->get();
        return comman_custom_response($post_job_status);
    }
    public function getPostRequestList(Request $request){
        $user = auth()->user();
        $post_request = PostJobRequest::myPostJob()->whereIn('status',['requested','accepted','assigned']);
        $per_page = config('constant.PER_PAGE_LIMIT');

        $orderBy = $request->orderby ? $request->orderby: 'desc';

        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $post_request->count();
            }
        }

        $post_request = $post_request->orderBy('id',$orderBy)->paginate($per_page);
        $items = PostJobRequestResource::collection($post_request);

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
    public function getPostRequestDetail(Request $request){
        $id = $request->post_request_id;
        $post_request = PostJobRequest::myPostJob()->find($id);
        if(empty($post_request)){
            $message = __('messages.record_not_found');
            return comman_message_response($message,400);   
        }
        $post_request_detail = new PostJobRequestDetailResource($post_request);
        $bider_data = PostJobBiderResource::collection(PostJobBid::where('post_request_id',$id)->get());
        $response = [
            'post_request_detail'    => $post_request_detail,
            'bider_data'    => $bider_data,
        ];

        return comman_custom_response($response);
    }
}