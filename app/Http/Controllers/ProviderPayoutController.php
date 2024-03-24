<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderPayout;
use App\Models\Booking;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Bank;
use App\Models\PaymentGateway;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Errors;
use Carbon\Carbon;

use App\Http\Requests\ProviderPayout as ProviderPayoutRequest;
use Yajra\DataTables\DataTables;

class ProviderPayoutController extends Controller
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
        return view('providerpayout.index', compact('pageTitle','auth_user','assets'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = ProviderPayout::query();
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
            return !empty($payout->payment_method) ? $payout->payment_method : 'cash';
        })
        ->editColumn('description', function($payout) {
            return !empty($payout->description) ? $payout->description : '-';
        })
        // ->editColumn('provider_id', function($payout) {
        //     return ($payout->providers != null && isset($payout->providers)) ? $payout->providers->display_name : '-';
        // })

        ->editColumn('provider_id', function ($payout) {
            return view('providerpayout.user', compact('payout'));
        })

        ->filterColumn('provider_id',function($payout,$keyword){
            $payout->whereHas('providers',function ($q) use($keyword){
                $q->where('first_name','like','%'.$keyword.'%');
            });
        })
        ->editColumn('amount', function($payout) {
            return ($payout->amount != null && isset($payout->amount)) ? getPriceFormat($payout->amount) : '-';
        })
        ->editColumn('created_at', function($payout) {
            return $payout->created_at;
        })
        ->addColumn('action', function($providerpayout){
            return view('providerpayout.action',compact('providerpayout'))->render();
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
                $branches = ProviderPayout::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Provider Payout Status Updated';
                break;

            case 'delete':
                ProviderPayout::whereIn('id', $ids)->delete();
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
        $pageTitle = trans('messages.add_button_form',['form' => trans('messages.provider_payout')]);
        $payoutdata = new ProviderPayout;

        $provider = User::with('providertype')->find($id);

        $bookings = Booking::where('provider_id',$id)->whereNotNull('payment_id')->get();

        $providerEarning = ProviderPayout::where('provider_id',$id)->sum('amount');

        $provider_commission = optional($provider->providertype)->commission;

        $provider_type = optional($provider->providertype)->type;

        $booking_data = get_provider_commission($bookings);

        $provider_earning = calculate_commission($booking_data['total_amount'],$provider_commission,$provider_type,'provider', $providerEarning,$bookings->count());

        if($provider_earning['number_format'] <= 0){
            if (request()->wantsJson()) {
                return response()->json(['messages' => __('messages.provider_earning_error'), 'status' => false]);
            } else {
                return redirect()->route('home')->with('error', __('messages.provider_earning_error'));
            }
        }

        $payoutdata->amount_formate = $provider_earning['value'];

        $provider_earning_value = number_format((float)$provider_earning['number_format'], 2, '.', '');

        $payoutdata->amount =(double)$provider_earning_value;
    
        $payoutdata->provider_id = $id;

        return view('providerpayout.create', compact('pageTitle' ,'payoutdata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderPayoutRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }

             $data = $request->except('_token');

             $payout_status='';

             $payment_gateway=$data['payment_gateway'];

             $provider_id=$data['provider_id'];

            if($data['payment_method'] === 'bank'){

              switch($payment_gateway){

               case 'razorpayx':
                
                 $response=providerpayout_rezopayX($data);

                 if($response==''){

                    return redirect()->back()->withErrors(trans('messages.rezorpayx_details'))->withInput();

                  }

                 $payout_details = json_decode($response,True);
                 $payout = $payout_details;
                  
                if($error=$payout['error']['description']  ==''){

                    $payout_id=$payout['id'] ;
                    // $payout_status=$payout['status'];
                    // $data['bank_id']=$data['bank'];
                    //$data['status']=$payout_status;
                    $data['paid_date']=Carbon::now();

                  }else {

                     $razorpay_message=$payout['error']['description'];

                     return  redirect()->back()->withErrors(trans('messages.razorpay_message',['razorpay_message' => $razorpay_message]))->withInput();
                
                   }  
               break;

               case 'stripe':

                $response=providerpayout_stripe($data);

               if($response==''){

                  return redirect()->back()->withErrors(trans('messages.stripe_details'))->withInput();

                 }else{

                 $status = $response->status;

               if($status==400){

                 $error_message = $response->code;

                 return  redirect()->back()->withErrors(trans('messages.stripe_message',['stripe_message' => $error_message]))->withInput();

                }else{

        

                 $payout_id=$response['id'];

                   $status='';

                   if($payout_id!=''){
                       
                        $status="paid";
                    }

                     $data['bank_id']=$data['bank'];
                     $data['status']=$status;
                     $data['paid_date']=Carbon::now();

                 }
               }

                break;

            }
             
         }

        $result = ProviderPayout::create($data);
     
        $activity_data = [
            'type' => 'provider_payout',
            'activity_type' => 'payout',
            'user_id' => $result->provider_id,
            'amount' => $result->amount,   
        ];
        savePayoutActivity($activity_data);

        if($result){
         
            if($data['payment_method'] === 'wallet'){
                $wallet = Wallet::where('user_id',$data['provider_id'])->first();
                if($wallet){
            
                    $wallet_amount = $wallet->amount;
                    $payout_amount = $result->amount;
                    $final_wallet_amount = $wallet_amount - $payout_amount;
                    $wallet->amount =  $final_wallet_amount;
                    $wallet->save();
                    $activity_data = [
                        'activity_type' => 'wallet_payout_transfer',
                        'transfer_amount' => $payout_amount ,
                        'wallet' => $wallet,
                    ];
            
                    saveWalletHistory($activity_data);
                }
            }
        }

        if ( request()->is('api*')){
          $message = __('messages.created_success',['form' => 'Provider Payout']);
          return comman_message_response($message);
        }

     if($payout_status=='queued'){

        return redirect()->route('earning')->with('success', __('messages.queue_message',['form' => 'Provider Payout']));

       }

        return redirect()->route('earning')->with('success', __('messages.created_success',['form' => 'Provider Payout']));

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
        $pageTitle = __('messages.list_form_title',['form' => __('messages.providerpayout_list')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('providerpayout.view', compact('pageTitle','auth_user','assets','id'));
    }


    public function ProviderPayout_index_data(DataTables $datatable,$id)
    {
        $query = ProviderPayout::where('provider_id',$id);
       
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newquery();
        }
        
        return $datatable ->eloquent($query)
        ->editColumn('payment_method', function($payout) {
            return !empty($payout->payment_method) ? $payout->payment_method : 'cash';
        })
        ->addColumn('bank_name', function($payout) {
           
        if($payout->payment_method == 'bank'){
            $bank = Bank::where('id',$payout->bank_id)->value('bank_name');
            return $bank;
        }
        else{
            return '-';
        }
        
        })
        ->editColumn('provider_id', function($payout) {
            return ($payout->providers != null && isset($payout->providers)) ? $payout->providers->display_name : '-';
        })
        ->editColumn('amount', function($payout) {
            return ($payout->amount != null && isset($payout->amount)) ? getPriceFormat($payout->amount) : '-';
        })
        ->editColumn('created_at', function($payout) {
            return $payout->created_at;
        })
        ->addIndexColumn()
        ->rawColumns(['bank_name'])
        ->toJson();
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
        $providerpayout = ProviderPayout::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.providerpayout')] );
        
        if($providerpayout != '') { 
            $providerpayout->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.providerpayout')] );
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
    
}

 