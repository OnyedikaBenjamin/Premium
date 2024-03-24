<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\API\CategoryResource;


class CategoryController extends Controller
{
    public function getCategoryList(Request $request){
        $category = Category::where('status',1);
        $auth_user = auth()->user();
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $category = new Category();
                $category = $category->withTrashed();
            }
        }
        if($request->has('is_featured')){
            $category->where('is_featured',$request->is_featured);
        }

        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $category->count();
            }
        }

        $category = $category->orderBy('name','asc')->paginate($per_page);
        $items = CategoryResource::collection($category);

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