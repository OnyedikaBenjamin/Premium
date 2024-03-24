<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documents;
use App\Http\Resources\API\DocumentResource;

class DocumentsController extends Controller
{
    public function getDocumentList(Request $request){

        $document = Documents::where('status',1);
        if(auth()->user() !== null){
            if(auth()->user()->hasRole('admin')){
                $document = Documents::withTrashed();
            }
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $document->count();
            }
        }

        $document = $document->orderBy('created_at','desc')->paginate($per_page);
        $items = DocumentResource::collection($document);

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