<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponServiceMapping;
use App\Models\Service;
use App\Http\Requests\CouponRequest;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.coupon')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('coupon.index', compact('pageTitle','auth_user','assets','filter'));
    }



    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Coupon::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->withTrashed();
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="coupon" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
          

            ->editColumn('code', function($query){                
                if (auth()->user()->can('coupon edit')) {
                    $link = '<a class="btn-link btn-link-hover" href='.route('coupon.create', ['id' => $query->id]).'>'.$query->code.'</a>';
                } else {
                    $link = $query->code; 
                }
                return $link;
            })
           
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input bg-primary change_status" '.$disabled.' data-type="coupon_status" '.($query->status ? "checked" : "").' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->editColumn('discount' , function ($query){
                $discount = getPriceFormat($query->discount);
                if($query->discount_type == 'percentage'){
                    $discount = $query->discount .'%';
                }
                return $discount;
            })
            ->addColumn('action', function($coupon){
                return view('coupon.action',compact('coupon'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['check','code','action','status'])
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
                $branches = Coupon::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Coupon Status Updated';
                break;

            case 'delete':
                Coupon::whereIn('id', $ids)->delete();
                $message = 'Bulk Coupon Deleted';
                break;
                
            case 'restore':
                Coupon::whereIn('id', $ids)->restore();
                $message = 'Bulk Coupon Restored';
                break;
            
            case 'permanently-delete':
                Coupon::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Coupon Permanently Deleted';
                break;

            case 'restore':
                Coupon::whereIn('id', $ids)->restore();
                $message = 'Bulk Provider Restored';
                break;
                
            case 'permanently-delete':
                Coupon::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Provider Permanently Deleted';
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
    public function create(Request $request)
    {
        $id = $request->id;
        $auth_user = authSession();

        $coupondata = Coupon::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.coupon')]);
        
        if($coupondata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.coupon')]);
            $coupondata = new Coupon;
        }
        
        return view('coupon.create', compact('pageTitle' ,'coupondata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();

        $data['expire_date'] = isset($request->expire_date) ? date('Y-m-d H:i:s',strtotime($request->expire_date)) : date('Y-m-d H:i:s');
        $result = Coupon::updateOrCreate(['id' => $data['id'] ],$data);

        if($request->type  == ''){
            if(count($data['service_id']) > 0){
                $result->serviceAdded()->delete();
                    if($data['service_id'] != null) {
                        foreach($data['service_id'] as $service) {
                            $service_data = [
                                'coupon_id'   => $result->id,
                                'service_id'  =>  $service
                            ];
                            $result->serviceAdded()->insert($service_data);
                        }
                    }
            }
        }
        $message = trans('messages.update_form',['form' => trans('messages.coupon')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.coupon')]);
        }
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
            
        return redirect(route('coupon.index'))->withSuccess($message);
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
        $coupon = Coupon::find($id);
        
        $msg = __('messages.msg_fail_to_delete',['item' => __('messages.coupon')] );
        
        if($coupon != '') { 

            $coupon->delete();
            $msg = __('messages.msg_deleted',['name' => __('messages.coupon')] );
        }
        if(request()->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }

    public function action(Request $request)
    {
        $id = $request->id;
        
        $coupon = Coupon::withTrashed()->where('id',$id)->first();

        $msg = __('messages.not_found_entry',['name' => __('messages.coupon')] );
        if($request->type == 'restore') {
            $coupon->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.coupon')] );
        }
        if($request->type === 'forcedelete'){
            $coupon->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.coupon')] );
        }
        if($request->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
