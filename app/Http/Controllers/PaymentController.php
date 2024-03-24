<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PaymentHistory;
use App\Models\Payment;
use App\Models\AppSetting;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
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
        $pageTitle = __('messages.list_form_title',['form' => __('messages.payment')] );
        $assets = ['datatable'];

        return view('payment.index', compact('pageTitle','assets','filter'));
    }

    public function cashIndex($id)
    {
        $pageTitle = __('messages.list_form_title',['form' => __('messages.cash_history')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('paymenthistory.index', compact('pageTitle','assets','auth_user','id'));
    }
    
     public function paymenthistory_index_data(DataTables $datatable,$id){
        $query = PaymentHistory::where('payment_id',$id);
       
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newquery();
        }
        
        return $datatable  ->eloquent($query)
        ->editColumn('booking_id', function($payment) {
            return ($payment->booking_id != null && isset($payment->booking->service)) ? $payment->booking->service->name :'-';
        })
        ->filterColumn('booking_id',function($query,$keyword){
            $query->whereHas('booking.service',function ($q) use($keyword){
                $q->where('name','like','%'.$keyword.'%');
            });
        })            
        ->editColumn('customer_id', function($payment) {
            return ($payment->booking != null && isset($payment->booking->customer)) ? $payment->booking->customer->display_name : '-';
        })
        ->filterColumn('customer_id', function ($query, $keyword) {
            $query->whereHas('booking', function ($q) use ($keyword) {
                $q->whereHas('customer', function ($c) use ($keyword) {
                    $c->where('display_name', 'like', '%' . $keyword . '%');
                });
            });
        })
        ->addIndexColumn()
        ->toJson();
     }

    public function cashDatatable(Request $request){
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = __('messages.list_form_title',['form' => __('messages.cash_payment')] );
        $assets = ['datatable'];
        return view('payment.cash', compact('pageTitle','assets','filter'));
    }

    public function cash_index_data(DataTables $datatable,Request $request)
    {
        $query = Payment::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query= $query->orderBy('id','desc')->where('payment_type','cash')->newQuery()->myPayment();
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
            })
            ->editColumn('id', function($payment) {
                if(isset($payment->booking) && $payment->booking !== null){
                    return '<a class="btn-link btn-link-hover" href='.route('booking.show', $payment->booking->id).'> #'.$payment->booking->id.'</a>';
                }
            })
            ->editColumn('booking_id', function($payment) {
                if($payment->customer_id != null && isset($payment->booking->service)){
                    return $payment->booking->service->name;
                }else{
                    return '-';
                }
            })
            ->filterColumn('booking_id',function($query,$keyword){
                $query->whereHas('booking.service',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            }) 
            ->editColumn('customer_id', function ($payment) {
                return view('payment.user', compact('payment'));
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('total_amount', function($payment) {
                return getPriceFormat($payment->total_amount);
            })
            ->editColumn('history', function($payment) {
                $action = '<a class="btn-link btn-link-hover"   href='.route('cash.index',$payment->id).'>View</a>';
                return $action;
            })
            ->editColumn('status', function($payment) {
                return last_status($payment->id);
            })
            ->filterColumn('status',function($query,$keyword){
                $query->whereHas('paymentHistory',function ($q) use($keyword){
                    $q->where('status','like','%'.$keyword.'%');
                });
            })
            // ->addColumn('action', function($payment){
            //     return view('payment.cashaction',compact('payment'))->render();
            // })
            ->editColumn('action', function($payment) {
                $action = set_admin_approved_cash($payment->id). ' ' .view('payment.cashaction',compact('payment'))->render();
                return $action;
               
            })
            ->addIndexColumn()->rawColumns(['check','history','action','id','status'])
            ->toJson();
    }



    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Payment::query();
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
        ->editColumn('booking_id', function($query) {


            return ($query->customer_id != null &&isset($query->booking->service)) ? $query->booking->service->name :'-';
        })
        ->filterColumn('booking_id',function($query,$keyword){
            $query->whereHas('booking.service',function ($q) use($keyword){
                $q->where('name','like','%'.$keyword.'%');
            });
        })            
        ->editColumn('customer_id', function ($payment) {
            return view('payment.user', compact('payment'));
        })
        ->filterColumn('customer_id',function($query,$keyword){
            $query->whereHas('customer',function ($q) use($keyword){
                $q->where('display_name','like','%'.$keyword.'%');
            });
        })
        ->editColumn('payment_status', function($query) {
            return ucwords(str_replace('_', ' ', $query->payment_status));
        })
      
        
        ->editColumn('total_amount', function($query) {
            return getPriceFormat($query->total_amount);
        })
        ->addColumn('action', function($payment){
            return view('payment.action',compact('payment'))->render();
        })
        ->addIndexColumn()
        ->rawColumns(['action','check'])
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
                $branches = Payment::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Category Status Updated';
                break;

            case 'delete':
                Payment::whereIn('id', $ids)->delete();
                $message = 'Bulk Category Deleted';
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
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $document = Payment::find($id);
        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.payment')] );
        
        if( $document!='') { 
        
            $document->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.payment')] );
        }
        if(request()->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }

    public function cashApprove($id){
        $admin = AppSetting::first();

        $paymentdata = Payment::where('id',$id)->first();
        $parent_payment_history = PaymentHistory::where('status','pending_by_admin')
        ->where('payment_id',$id)->first();
        


        $payment_history = [
            'payment_id' => $id,
            'booking_id' => $paymentdata->booking_id,
            'action' => config('constant.PAYMENT_HISTORY_ACTION.ADMIN_APPROVED_CASH'),
            'type' => $parent_payment_history->payment_type,
            'sender_id' => $parent_payment_history->sender_id,
            'receiver_id' => admin_id(),
            'total_amount' => $paymentdata->total_amount,
            'text' =>  __('messages.cash_approved',['amount' => getPriceFormat((float)$paymentdata->total_amount),'name' => get_user_name(admin_id())]),
            'status' => config('constant.PAYMENT_HISTORY_STATUS.APPROVED_ADMIN'),
            'parent_id' => $parent_payment_history->parent_id
        ];

        date_default_timezone_set( $admin->time_zone ?? 'UTC');
        $payment_history['datetime'] = date('Y-m-d H:i:s');

        if(!empty($paymentdata->txn_id)){
            $payment_history['txn_id'] =$paymentdata->txn_id;
        }
        if(!empty($paymentdata->other_transaction_detail)){
            $payment_history['other_transaction_detail'] =$paymentdata->other_transaction_detail;
        }
        $res = PaymentHistory::create($payment_history);

        $parent_record = PaymentHistory::where('parent_id',$parent_payment_history->parent_id)->first();

        $parent_payment_history->status = 'approved_by_admin';
        $parent_payment_history->update();

        $parent_record->status = 'approved_by_admin';
        $parent_record->update();


        $paymentdata->payment_status = 'paid';
        $paymentdata->update();
        
        $msg = __('messages.approve_successfully');
        return redirect()->back()->withSuccess($msg);
    }

}
