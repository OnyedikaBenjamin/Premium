<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Resources\API\BlogResource;


class BlogController extends Controller
{
    public function getBlogList(Request $request){  
        $blog = Blog::orderBy('id','desc');
        if(!empty($request->provider_id)){
            $blog = $blog->where('author_id',$request->provider_id);
        }
        if(!empty($request->status)){
            $blog = $blog->where('status',$request->status);
        }
        $auth_user = auth()->user();
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $blog = new Blog();
                $blog = $blog->withTrashed();
            }
        }
        if($request->has('is_featured')){
            $blog->where('is_featured',$request->is_featured);
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $blog->count();
            }
        }

        $blog = $blog->paginate($per_page);
        $items = BlogResource::collection($blog);

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
    public function getBlogDetail(Request $request){
        $id = $request->blog_id;
        $customer_id = $request->customer_id;
       
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $blog = Blog::withTrashed()->findorfail($id);
            }
            else{
                $blog = Blog::findorfail($id);
            }
        }else{
            $blog = Blog::findorfail($id);
        }
        if(empty($blog)){
            $message = __('messages.record_not_found');
            return comman_message_response($message,400);   
        }
        if($customer_id !== $blog->author_id){
            $blog->total_views = $blog->total_views + 1;
            $blog->update();
        }
        $blog_detail = new BlogResource($blog);
        
        $response = [
            'blog_detail'    => $blog_detail
        ];

        return comman_custom_response($response);
    }

}