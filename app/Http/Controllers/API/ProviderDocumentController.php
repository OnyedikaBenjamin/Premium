<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProviderDocument;
use App\Http\Resources\API\ProviderDocumentResource;

class ProviderDocumentController extends Controller
{
    public function getProviderDocumentList(Request $request){
        $provider_id  = !empty($request->provider_id) ? $request->provider_id : auth()->user()->id;
        $provider_document = ProviderDocument::where('provider_id',$provider_id);
        if(auth()->user()->hasRole('admin')){
            $provider_document = ProviderDocument::withTrashed();
            if( $request->has('provider_id') && !empty($request->provider_id)){
                $provider_document = ProviderDocument::where('provider_id',$provider_id)->withTrashed();
            }
        }
        $per_page = config('constant.PER_PAGE_LIMIT');
        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $provider_document->count();
            }
        }

        $provider_document = $provider_document->orderBy('created_at','desc')->paginate($per_page);
        $items = ProviderDocumentResource::collection($provider_document);

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