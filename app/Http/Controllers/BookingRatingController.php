<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingRating;
use Yajra\DataTables\DataTables;

class BookingRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = __('messages.user_ratings');
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('bookingrating.index', compact('pageTitle','auth_user','assets','filter'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = BookingRating::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newQuery();
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
            })
            // ->editColumn('customer_id', function($query){
            //     return ($query != null && isset($query->customer)) ? $query->customer->first_name : '-';
            // })
            ->editColumn('customer_id', function($query){
                return view('bookingrating.user', compact('query'));
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('service_id', function($query){
                return ($query != null && isset($query->service)) ? $query->service->name : '-';
            })
            ->editColumn('review', function($query){
                return ($query != null && isset($query->review)) ? $query->review : '-';
            })
            ->addColumn('action', function($bookingrating){
                return view('bookingrating.action', compact('bookingrating'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'check'])
            ->toJson();
    }

    /* bulck action method */
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;
       
        $message = 'Bulk Action Updated';
        switch ($actionType) {
            case 'change-status':
                $branches = BookingRating::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk BookingRating Status Updated';
                break;

            case 'delete':
                BookingRating::whereIn('id', $ids)->delete();
                $message = 'Bulk BookingRating Deleted';
                break;

            default:
                return response()->json(['status' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'message' => 'Bulk Action Updated']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            return redirect()->back()->withErrors(trans('messages.demo.permission.denied'));
        }
        $bookingrating = BookingRating::find($id);
        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.user_ratings')] );

        if($bookingrating != ''){
            $bookingrating->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.user_ratings')] );
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
}
