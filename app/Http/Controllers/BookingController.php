<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Service;
use App\Models\Payment;
use App\Models\User;
use App\Models\BookingCouponMapping;
use App\Models\BookingActivity;
use App\Models\BookingStatus;
use App\Models\PostJobRequest;
use App\Models\ProviderAddressMapping;
use App\Http\Requests\BookingUpdateRequest;
use App\Models\Notification;
use Yajra\DataTables\DataTables;
use PDF; 
use App\Models\AppSetting;
use Carbon\Carbon;
class BookingController extends Controller
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
        $pageTitle = __('messages.list_form_title',['form' => __('messages.booking')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('booking.index', compact('pageTitle','auth_user','assets','filter'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Booking::query();
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
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="booking" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
            ->editColumn('id' , function ($query){
                return "<a class='btn-link btn-link-hover' href=" .route('booking.show', $query->id).">#".$query->id ."</a>";
            })
            // ->editColumn('customer_id' , function ($query){
            //     return ($query->customer_id != null && isset($query->customer)) ? $query->customer->display_name : '';
            // })
            ->editColumn('customer_id' , function ($query){
                return view('booking.customer', compact('query'));
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('service_id' , function ($query){
                $service_name = ($query->service_id != null && isset($query->service)) ? $query->service->name : "";
                return "<a class='btn-link btn-link-hover' href=" .route('booking.show', $query->id).">".$service_name ."</a>";
            })
            ->filterColumn('service_id',function($query,$keyword){
                $query->whereHas('service',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            })
            // ->editColumn('provider_id' , function ($query){
            //     return ($query->provider_id != null && isset($query->provider)) ? $query->provider->display_name : '';
            // })
            ->editColumn('provider_id' , function ($query){
                return view('booking.provider', compact('query'));
            })
            ->filterColumn('provider_id',function($query,$keyword){
                $query->whereHas('provider',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('status' , function ($query){
                return bookingstatus(BookingStatus::bookingStatus($query->status));
            })
            ->editColumn('payment_id' , function ($query){
                $payment_status = optional($query->payment)->payment_status;
                if($payment_status !== 'paid'){
                    $status = '<span class="badge badge-pay-pending">'.__('messages.pending').'</span>';
                }else{
                    $status = '<span class="badge badge-paid">'.__('messages.paid').'</span>';
                }
                return  $status;
            })
            ->filterColumn('payment_id',function($query,$keyword){
                $query->whereHas('payment',function ($q) use($keyword){
                    $q->where('payment_status','like',$keyword.'%');
                });
            })
            ->editColumn('total_amount' , function ($query){
                return $query->total_amount ? getPriceFormat($query->total_amount) : '-';
            })

            ->addColumn('action', function($booking){
                return view('booking.action',compact('booking'))->render();
            })

            ->editColumn('updated_at', function ($query) {
                $diff = Carbon::now()->diffInHours($query->updated_at);
                if ($diff < 25) {
                    return $query->updated_at->diffForHumans();
                } else {
                    return $query->updated_at->isoFormat('llll');
                }
            })
            ->addIndexColumn()
            ->rawColumns(['action','status','payment_id','service_id','id','check'])
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
                $branches = Booking::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Booking Status Updated';
                break;

            case 'delete':
                Booking::whereIn('id', $ids)->delete();
                $message = 'Bulk Booking Deleted';
                break;

            case 'restore':
                Booking::whereIn('id', $ids)->restore();
                $message = 'Bulk Booking Restored';
                break;
                
            case 'permanently-delete':
                Booking::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Booking Permanently Deleted';
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

        $bookingdata = Booking::find($id);
        $pageTitle = __('messages.update_form_title',['form'=> __('messages.booking')]);
        
        if($bookingdata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.booking')]);
            $bookingdata = new Booking;
        }
        
        return view('booking.create', compact('pageTitle' ,'bookingdata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        $data = $request->all();

        if($request->id == null)
        {
            $data['status'] = !empty($data['status']) ? $data['status'] :'pending';
        }       
        $data['date'] = isset($request->date) ? date('Y-m-d H:i:s',strtotime($request->date)) : date('Y-m-d H:i:s');
        $service_data = Service::find($data['service_id']); 

        $data['provider_id'] = !empty($data['provider_id']) ? $data['provider_id']: $service_data->provider_id;
       
        if($request->has('tax') && $request->tax != null) {
            $data['tax'] = json_encode($request->tax);
        }
   
        if($request->coupon_id != null) {
            $coupons = Coupon::with('serviceAdded')->where('code',$request->coupon_id)
                ->where('expire_date','>',date('Y-m-d H:i'))->where('status',1)
                ->whereHas('serviceAdded', function($coupon) use($service_data){
                    $coupon->where('service_id', $service_data->id );
                })->first();
            if( $coupons == null ) {
                return comman_message_response( __('messages.invalid_coupon_code'),406);
            } else {
                $data['coupon_id'] = $coupons->id;
            }
        }
                                                                        
        $result = Booking::updateOrCreate(['id' => $request->id], $data);
       
        $activity_data = [
            'activity_type' => 'add_booking',
            'booking_id' => $result->id,
            'booking' => $result,
        ];

        saveBookingActivity($activity_data);

        if($data['coupon_id'] != null) {
            $coupons = Coupon::find($data['coupon_id']);
            
            $coupon_data = [
                'booking_id'    => $result->id,
                'code'          => $coupons->code,
                'discount'      => $coupons->discount,
                'discount_type' => $coupons->discount_type,
            ];
            
            $result->couponAdded()->create($coupon_data);
        }
        if($request->has('booking_address_id') && $request->booking_address_id != null) {
            $booking_address_mapping = ProviderAddressMapping::find($data['booking_address_id']);
            
            $booking_address_data = [
                'booking_id'    => $result->id,
                'address'          => $booking_address_mapping->address,
                'latitude'      => $booking_address_mapping->latitude,
                'longitude' => $booking_address_mapping->longitude,
            ];
            
            $result->addressAdded()->create($booking_address_data);
        }
        if($request->has('booking_package') && $request->booking_package != null) {
            $booking_package = [
               'booking_id' => $result->id,
               'service_package_id' => $data['booking_package']['id'],
               'provider_id' => $data['provider_id'],
               'name' => $data['booking_package']['name'],
               'is_featured' => $data['booking_package']['is_featured'],
               'package_type' => $data['booking_package']['package_type'],
               'price' => $data['booking_package']['price'],
            ]; 
            if(!empty($data['booking_package']['start_at'])){
                $booking_package['start_at'] = $data['booking_package']['start_at'];
            }
            if(!empty($data['booking_package']['end_at'])){
                $booking_package['end_at'] = $data['booking_package']['end_at'];
            }
            if(!empty($data['booking_package']['subcategory_id'])){
                $booking_package['subcategory_id'] = $data['booking_package']['subcategory_id'];
            }
            if(!empty($data['booking_package']['category_id'])){
                $booking_package['category_id'] = $data['booking_package']['category_id'];
            }
            $result->bookingPackage()->create($booking_package);
       }
        if(!empty($data['type']) && $data['type'] === 'user_post_job'){
            $post_request = PostJobRequest::where('id',$data['post_request_id'])->first();
            $post_request->date = isset($request->date) ? date('Y-m-d H:i:s',strtotime($request->date)) : date('Y-m-d H:i:s');
            $post_request->update();
        }
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.booking') ] );
		}

        if($request->is('api/*')) {
            $response = [
                'message'=>$message,
                'booking_id' => $result->id
            ];
            return comman_custom_response($response);
		}
		return  redirect(route('booking.index'))->withSuccess($message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $auth_user = authSession();

         $user = auth()->user();
         $user->last_notification_seen = now();
         $user->save();

         if(count($user->unreadNotifications) > 0 ) {

           foreach($user->unreadNotifications as $notifications){

              if($notifications['data']['id'] == $id){

                 $notification = $user->unreadNotifications->where('id', $notifications['id'])->first();
                if($notification){
                     $notification->markAsRead();
                       }
                  }
        
             }
                  
        }

    
        $bookingdata = Booking::with('bookingExtraCharge','payment')->myBooking()->find($id);

       
        $tabpage = 'info';
        if (empty($bookingdata)) {
            $msg = __('messages.not_found_entry', ['name' => __('messages.booking')]);
            return redirect(route('booking.index'))->withError($msg);
        }
        if (count($auth_user->unreadNotifications) > 0) {
            $auth_user->unreadNotifications->where('data.id', $id)->markAsRead();
        }

        $pageTitle = __('messages.view_form_title', ['form' => __('messages.booking')]);
        return view('booking.view', compact('pageTitle', 'bookingdata', 'auth_user', 'tabpage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auth_user = authSession();

        $bookingdata = Booking::myBooking()->find($id);
   
        $pageTitle = __('messages.update_form_title',['form'=> __('messages.booking')]);
        $relation = [
            'status' => BookingStatus::where('status',1)->orderBy('sequence','ASC')->get()->pluck('label', 'value'),
        ];
        return view('booking.edit', compact('pageTitle' ,'bookingdata' ,'auth_user' )+$relation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookingUpdateRequest $request, $id)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
        
        
        $data['date'] = isset($request->date) ? date('Y-m-d H:i:s',strtotime($request->date)) : date('Y-m-d H:i:s');
        $data['start_at'] = isset($request->start_at) ? date('Y-m-d H:i:s',strtotime($request->start_at)) : null;
        $data['end_at'] = isset($request->end_at) ? date('Y-m-d H:i:s',strtotime($request->end_at)) : null;

        
        $bookingdata = Booking::find($id);
        $paymentdata = Payment::where('booking_id',$id)->first();
        if($data['status'] === 'hold'){
            if($bookingdata->start_at == null && $bookingdata->end_at == null){
                $duration_diff = duration($data['start_at'] ,$data['end_at'] ,'in_minute');
                $data['duration_diff'] = $duration_diff;
            }else{
                if($bookingdata->status == $data['status']){
                    $booking_start_date = $bookingdata->start_at;
                    $request_start_date = $data['start_at'];
                    if($request_start_date > $booking_start_date){
                        $msg = __('messages.already_in_status',[ 'status' => $data['status'] ] );
                        return redirect()->back()->withSuccess($msg);
                    }
                }else{
                    $duration_diff = $bookingdata->duration_diff;
                    $new_diff = duration($bookingdata->start_at ,$bookingdata->end_at ,'in_minute');
                    $data['duration_diff'] = $duration_diff + $new_diff;
                }
            }
        }
        if($bookingdata->status != $data['status']) {
            $activity_type = 'update_booking_status';
        }
        if($data['status'] == 'cancelled'){
            $activity_type = 'cancel_booking';
        }
        $data['reason'] = isset($data['reason']) ? $data['reason'] : null;
        $old_status = $bookingdata->status;
        $bookingdata->update($data);
        if($old_status != $data['status']){
            $bookingdata->old_status = $old_status;
            $activity_data = [
                'activity_type' => $activity_type,
                'booking_id' => $id,
                'booking' => $bookingdata,
            ];
    
            saveBookingActivity($activity_data);
        }
        if($bookingdata->payment_id != null){
            $data['payment_status'] = isset($data['payment_status']) ? $data['payment_status'] : 'pending';
            $paymentdata->update($data);

            if($bookingdata->payment_id != null){
                $data['payment_status'] = isset($data['payment_status']) ? $data['payment_status'] : 'pending';
                $paymentdata->update($data);
                $activity_data = [
                    'activity_type' => 'payment_message_status',
                    'payment_status'=> $data['payment_status'],
                    'booking_id' => $id,
                    'booking' => $bookingdata,
                ];
                saveBookingActivity($activity_data);
            }
        }
        $message = __('messages.update_form',[ 'form' => __('messages.booking') ] );

        if($request->is('api/*')) {
            return comman_message_response($message);
		}

		return  redirect(route('booking.index'))->withSuccess($message);
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
        $booking = Booking::find($id);

        $msg = __('messages.msg_fail_to_delete',['item' => __('messages.booking')] );
        
        if($booking != '') {
            Notification::whereJsonContains('data->id',$booking->id)->delete();
            $booking->delete();
            $msg = __('messages.msg_deleted',['name' => __('messages.booking')] );
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }

    public function  bookingAssignForm(Request $request){

        $bookingdata = Booking::find($request->id);
        $pageTitle = __('messages.assign_form_title',['form'=> __('messages.booking')]);
        return view('booking.assigned_form',compact('bookingdata','pageTitle'));
    }

    public function bookingAssigned(Request $request)
    {
        $bookingdata =  Booking::find($request->id);

        $assigned_handyman_ids = [];
        if($bookingdata->handymanAdded()->count() > 0){
            $assigned_handyman_ids = $bookingdata->handymanAdded()->pluck('handyman_id')->toArray();
            $bookingdata->handymanAdded()->delete();
            $message = __('messages.transfer_to_handyman');
            $activity_type = 'transfer_booking';
        } else {
            $message = __('messages.assigned_to_handyman');
            $activity_type = 'assigned_booking';
        }

        $remove_notification_id = [];
        if($request->handyman_id != null) {
            foreach($request->handyman_id as $handyman) {
                $assign_to_handyman = [
                    'booking_id'   => $bookingdata->id,
                    'handyman_id'  => $handyman
                ];
                $remove_notification_id = removeArrayValue($handyman,$assigned_handyman_ids);
                $bookingdata->handymanAdded()->insert($assign_to_handyman);
            }
        }

        if(!empty($remove_notification_id)){
            $search = "id".'":'.$bookingdata->id;
            
            Notification::whereIn('notifiable_id',$remove_notification_id)
                ->whereJsonContains('data->id',$bookingdata->id)
                ->delete();
        }

        $bookingdata->status = 'accept';
        $bookingdata->save();

        $activity_data = [
            'activity_type' => $activity_type,
            'booking_id' => $bookingdata->id,
            'booking' => $bookingdata,
        ];

        saveBookingActivity($activity_data);
        $message = __('messages.save_form',[ 'form' => __('messages.booking') ] );
        if($request->is('api/*')) {
            return comman_message_response($message);
		}

        return response()->json(['status' => true,'event' => 'callback' , 'message' => $message]);
    }

    public function action(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $booking_data = Booking::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.booking')] );
        if($request->type === 'restore'){
            if($booking_data != ''){
                $booking_data->restore();
                $msg = __('messages.msg_restored',['name' => __('messages.booking')] );
            }
        }
        if($request->type === 'forcedelete'){
            $booking_data->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.booking')] );
        }

        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
    public function bookingDetails(Request $request, $id)
    {
        $auth_user = authSession();
        $providerdata = User::with('providerBooking')->where('user_type', 'provider')->where('id', $id)->first();
        $earningData = array();
        foreach ($providerdata->providerBooking as $booking) {
            $booking_id = $booking->id;
            $provider_name = optional($booking->provider)->display_name ?? '-';
            $provider_contact = optional($booking->provider)->contact_number ?? '-';
            $amount = $booking->amount;
            $payment_status = optional($booking->payment)->payment_status ?? '-';
            $start_at = $booking->start_at;
            $end_at = $booking->end_at;
            $earningData[] = [
                'provider_id' => $providerdata->id,
                'booking_id' => $booking->id,
                'provider_name' => $provider_name,
                'provider_contact' => $provider_contact,
                'amount' => $amount,
                'payment_status' => $payment_status,
                'start_at' => $start_at,
                'end_at' => $end_at,
            ];
        }
        if ($request->ajax()) {
            return Datatables::of($earningData)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '-';
                    $booking_id = $row['booking_id'];
                    $btn = "<a href=" . route('booking.show', $booking_id) . "><i class='fas fa-eye'></i></a>";
                    return $btn;
                })
                ->editColumn('payment_status' , function ($row){
                    $payment_status = $row['payment_status'];
                    if($payment_status == 'pending'){
                        $status = '<span class="badge badge-danger">'.__('messages.pending').'</span>';
                    }else{
                        $status = '<span class="badge badge-success">'.__('messages.paid').'</span>';
                    }
                    return  $status;
                })
                ->editColumn('amount' , function ($row){
                    return $row['amount'] ? getPriceFormat($row['amount']) : '-';
                })
                ->rawColumns(['action','payment_status','amount'])
                ->make(true);
        }
        if (empty($providerdata)) {
            $msg = __('messages.not_found_entry', ['name' => __('messages.provider')]);
            return redirect(route('provider.index'))->withError($msg);
        }
        $pageTitle = __('messages.view_form_title', ['form' => __('messages.provider')]);
        return view('booking.details', compact('pageTitle', 'earningData', 'auth_user', 'providerdata'));
    }
    public function bookingstatus(Request $request, $id)
    {
        $tabpage = $request->tabpage;
        $auth_user = authSession();
        $user_id = $auth_user->id;
        $user_data = User::find($user_id);
        $bookingdata = Booking::with('handymanAdded', 'payment', 'bookingExtraCharge')->myBooking()->find($id);
        switch ($tabpage) {
            case 'info':
                $data  = view('booking.' . $tabpage, compact('user_data', 'tabpage', 'auth_user', 'bookingdata'))->render();
                break;
            case 'status':
                $data  = view('booking.' . $tabpage, compact('user_data', 'tabpage', 'auth_user', 'bookingdata'))->render();
                break;
            default:
                $data  = view('booking.' . $tabpage, compact('tabpage', 'auth_user', 'bookingdata'))->render();
                break;
        }
        return response()->json($data); 
    }
    public function createPDF($id)
    {
        $data =AppSetting::take(1)->first();
    $bookingdata = Booking::with('handymanAdded', 'payment', 'bookingExtraCharge')->myBooking()->find($id);
    $pdf = Pdf::loadView('booking.invoice',['bookingdata'=>$bookingdata ,'data'=> $data]);
    return $pdf->download('invoice.pdf');
    }

    public function updateStatus(Request $request)
    {
        
        switch ($request->type) {
            case 'payment':
                $data = Payment::where('booking_id',$request->bookingId)->update(['payment_status'=>$request->status]);
                break;
                default:
                
                $data = Booking::find($request->bookingId)->update(['status'=>$request->status]);
                break;
        }
 
        return comman_custom_response(['message'=> 'Status Updated' , 'status' => true]);
    }
}