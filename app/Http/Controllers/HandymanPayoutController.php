<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\HandymanPayoutRequest;
use App\Models\HandymanPayout;
use App\Models\HandymanType;
use App\Models\User;
use App\Models\BookingHandymanMapping;
use App\Models\ProviderPayout;
use Yajra\DataTables\DataTables;

class HandymanPayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = trans('messages.payout_history' );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('handymanpayout.index', compact('pageTitle','auth_user','assets'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = HandymanPayout::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newquery();
        }
        
        return $datatable->eloquent($query)
        ->addColumn('check', function ($row) {
            return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
        })
        ->editColumn('payment_method', function($payout) {
            return !empty($payout->method) ? $payout->method : 'Cash';
        })
        ->editColumn('description', function($payout) {
            return !empty($payout->description) ? $payout->description : '-';
        })
        // ->editColumn('handyman_id', function($payout) {
        //     return ($payout->handymans != null && isset($payout->handymans)) ? $payout->handymans->display_name : '-';
        // })

        ->editColumn('handyman_id', function ($payout) {
            return view('handymanpayout.user', compact('payout'));
        })

        ->filterColumn('handyman_id',function($payout,$keyword){
            $payout->whereHas('handymans',function ($q) use($keyword){
                $q->where('first_name','like','%'.$keyword.'%');
            });
        })
        ->editColumn('amount', function($payout) {
            return ($payout->amount != null && isset($payout->amount)) ? getPriceFormat($payout->amount) : '-';
        })
        ->editColumn('created_at', function($payout) {
            return $payout->created_at;
        })
        ->addColumn('action', function($handymanpayout){
            return view('handymanpayout.action',compact('handymanpayout'))->render();
        })
        ->addIndexColumn()
        ->rawColumns(['check','title','action','status'])
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
                $branches = HandymanPayout::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Provider Payout Status Updated';
                break;

            case 'delete':
                HandymanPayout::whereIn('id', $ids)->delete();
                $message = 'Bulk Provider Payout Deleted';
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
    public function create($id)
    {
        $auth_user = authSession();

        $payoutdata = new HandymanPayout;

        $pageTitle = trans('messages.add_button_form',['form' => trans('messages.handyman_payout')]);

        $handymandata = User::where('id',$id)->first();

        $handymantype_id = !empty($handymandata->handymantype_id) ? $handymandata->handymantype_id : 1;
        $get_commission = HandymanType::withTrashed()->where('id',$handymantype_id)->first();

        $totalEarning = HandymanPayout::where('handyman_id',$id)->sum('amount');
        
        $commission = $get_commission->commission;

        $handyman_bookings = BookingHandymanMapping::with('bookings')->where('handyman_id',$id)->whereHas('bookings',function ($q){
            $q->whereNotNull('payment_id');
        })->get();

        $all_booking_total = $handyman_bookings->map(function ($booking) {
            return optional($booking->bookings)->total_amount;
        })->toArray();

        $total = array_reduce($all_booking_total, function ($value1, $value2) {
            return $value1 + $value2;
        }, 0);
        
        $earning =   ($commission * count($handyman_bookings));
        if($get_commission->type === 'percent'){
            $earning =  ($total) * $commission / 100;
        }
        $final_amount = $earning - $totalEarning;

        $providerEarning = ProviderPayout::where('provider_id',$handymandata->provider_id)->sum('amount');
       
        if($earning <= 0){
            if (request()->wantsJson()) {
                return response()->json(['messages' => __('messages.provider_earning_error'), 'status' => false]);
            } else {
                return redirect()->route('home')->with('error', __('messages.provider_earning_error'));
            }
        }
        

        $payoutdata->amount = number_format((float)$final_amount, 2, '.', '');

        $payoutdata->handyman_id = $id;

        return view('handymanpayout.create', compact('pageTitle' ,'payoutdata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_user = authSession();
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->except('_token');
        $handymandata = User::where('id',$data['handyman_id'])->first();

        $providerEarning = ProviderPayout::where('provider_id',$handymandata->provider_id)->sum('amount');
        $handymanEarning = HandymanPayout::where('handyman_id',$handymandata->id)->sum('amount');
        $providerEarning = $providerEarning-$handymanEarning;
        if($providerEarning < $data['amount']){
            return redirect()->back()->with('error', __('messages.less_provider_earning',['amount'=>$providerEarning]));
        }
        $result = HandymanPayout::create($data);
        $activity_data = [
            'type' => 'handyman_payout',
            'activity_type' => 'payout',
            'user_id' => $result->handyman_id,
            'amount' => $result->amount,
        ];
        savePayoutActivity($activity_data);
    
        return redirect()->route('handymanEarning')->with('success', __('messages.created_success',['form' => 'Handyman Payout']));
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
        $handymanpayout = HandymanPayout::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.handymanpayout')] );
        
        if($handymanpayout != '') { 
            $handymanpayout->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.handymanpayout')] );
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
}
