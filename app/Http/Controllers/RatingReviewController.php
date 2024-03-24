<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingRating;
use Yajra\DataTables\DataTables;

class RatingReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.rating')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('ratingreview.index', compact('pageTitle','auth_user','assets'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = BookingRating::query();
      
        if(auth()->user()->hasAnyRole(['admin'])){
            $query = $query->withTrashed();
        }
        return $datatable->eloquent($query)
        ->editColumn('customer_id' , function ($rating_review){
            return ($rating_review->customer_id != null && isset($rating_review->customer)) ? $rating_review->customer->display_name : '';
        })
        ->filterColumn('customer_id',function($query,$keyword){
            $query->whereHas('customer',function ($q) use($keyword){
                $q->where('display_name','like','%'.$keyword.'%');
            });
        })
        ->editColumn('service_id' , function ($rating_review){
            return ($rating_review->service_id != null && isset($rating_review->service)) ? $rating_review->service->name : '';
        })
        ->filterColumn('service_id',function($query,$keyword){
            $query->whereHas('service',function ($q) use($keyword){
                $q->where('name','like','%'.$keyword.'%');
            });
        })
        ->editColumn('rating' , function ($rating_review){
            return $rating_review->rating .' <i class="ri-star-line"></i>';
        })
        ->addColumn('action', function($rating_review){
            return view('ratingreview.action',compact('rating_review'))->render();
        })
        ->addIndexColumn()
        ->rawColumns(['action','rating'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $auth_user = authSession();

        $rating_review = BookingRating::with('customer')->find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.rating')]);
        
        return view('ratingreview.create', compact('pageTitle' ,'rating_review' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(demoUserPermission()){
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
        $result = BookingRating::updateOrCreate(['id' => $data['id'] ],$data);

        $message = trans('messages.update_form',['form' => trans('messages.rating')]);
        return redirect(route('ratingreview.index'))->withSuccess($message);       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $rating_review = BookingRating::find($id);
        
        if( $rating_review!='') { 
        
            $rating_review->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.rating')] );
        }
        return redirect()->back()->withSuccess($msg);
    }

    public function action(Request $request){
        $id = $request->id;

        $document  = BookingRating::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.rating')] );
        if($request->type == 'restore') {
            $document->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.rating')] );
        }
        if($request->type === 'forcedelete'){
            $document->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.rating')] );
        }
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
