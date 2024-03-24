<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\User;
use App\Models\Setting;
use App\Models\BookingRating;
use App\Models\CouponServiceMapping;
use App\Models\ProviderServiceAddressMapping;
use App\Http\Requests\ServiceRequest;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
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
        $pageTitle = __('messages.list_form_title',['form' => __('messages.service')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('service.index', compact('pageTitle','auth_user','assets','filter'));  
    }

    // get datatable data
    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Service::query();

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query = $query->where('service_type','service')->withTrashed();

        }

        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {

                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="service" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
        
            ->editColumn('name', function($query){                
                if (auth()->user()->can('service edit')) {
                    $link =  '<a class="btn-link btn-link-hover" href='.route('service.create', ['id' => $query->id]).'>'.$query->name.'</a>';
                } else {
                    $link = $query->name; 
                }
                return $link;
            })
            ->editColumn('category_id' , function ($query){
                return ($query->category_id != null && isset($query->category)) ? $query->category->name : '-';
            })
            ->filterColumn('category_id',function($query,$keyword){
                $query->whereHas('category',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            })
              // ->editColumn('provider_id' , function ($query){
            //     return ($query->provider_id != null && isset($query->providers)) ? $query->providers->display_name : '';
            // })
            ->editColumn('provider_id' , function ($query){
                return view('service.service', compact('query'));
            })
            ->filterColumn('provider_id',function($query,$keyword){
                $query->whereHas('providers',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('price' , function ($query){
                return getPriceFormat($query->price).'-'.ucFirst($query->type);
            })
            
            ->editColumn('discount' , function ($query){
                return $query->discount ? $query->discount .'%' : '-';
            })
            ->addColumn('action', function ($data) {
                return view('service.action', compact('data'));
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="service_status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
           
            ->rawColumns(['action', 'status', 'check','name'])
            ->toJson();
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';

        
        switch ($actionType) {
            case 'change-status':
                $branches = Service::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Service Status Updated';
                break;

            case 'delete':
                Service::whereIn('id', $ids)->delete();
                $message = 'Bulk Service Deleted';
                break;

            case 'restore':
                Service::whereIn('id', $ids)->restore();
                $message = 'Bulk Service Restored';
                break;
                
            case 'permanently-delete':
                Service::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Service Permanently Deleted';
                break;

            default:
                return response()->json(['status' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'message' => 'Bulk Action Updated']);
    }



    /* user service list */
    public function getUserServiceList(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];
        $pageTitle = __('messages.list_form_title',['form' => __('messages.service')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('service.user_service_list', compact('pageTitle','auth_user','assets','filter'));
        
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

        $servicedata = Service::find($id);

       

        $settingdata = Setting::where('type','=','ADVANCED_PAYMENT_SETTING')->first();
         
        $pageTitle = __('messages.update_form_title',['form'=> __('messages.service')]);
        
        if($servicedata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.service')]);
            $servicedata = new Service;
        }
        
        return view('service.create', compact('pageTitle' ,'servicedata' ,'auth_user' , 'settingdata'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
       
        $services = $request->all();
      
        $services['service_type'] = !empty($request->service_type) ? $request->service_type : 'service';
        $services['provider_id'] = !empty($request->provider_id) ? $request->provider_id : auth()->user()->id;
        if(auth()->user()->hasRole('user')){
            $services['service_type'] = 'user_post_service';
        }
        
        if($request->id == null && default_earning_type() === 'subscription'){
           $exceed =  get_provider_plan_limit($services['provider_id'],'service');
           if(!empty($exceed)){
            if($exceed == 1){
                $message = __('messages.limit_exceed',['name'=>__('messages.service')]);
            }else{
                 $message = __('messages.not_in_plan',['name'=>__('messages.service')]);
            }
             if($request->is('api/*')){
                 return comman_message_response($message);
             }else{
                 return  redirect()->back()->withErrors($message);
             }
           }
        }
     
        if($request->id == null){
            $services['added_by'] =  !empty($request->added_by) ? $request->added_by :auth()->user()->id;
        }

        $services['provider_id'] = !empty( $services['provider_id'] ) ?  $services['provider_id']     : auth()->user()->id;
        if(!empty($services['is_featured']) && $services['is_featured'] == 1){
            $exceed =  get_provider_plan_limit($services['provider_id'],'featured_service');
            if(!empty($exceed)){
                if($exceed == 1){
                    $message = __('messages.limit_exceed',['name'=>__('messages.featured_service')]);
                }else{
                        $message = __('messages.not_in_plan',['name'=>__('messages.featured_service')]);
                }
                if($request->is('api/*')){
                    return comman_message_response($message);
                }else{
                    return  redirect()->back()->withErrors($message);
                }
            }
        }
        if(!$request->is('api/*')) {
            $services['is_featured'] = 0;
            $services['is_slot'] = 0;
            $services['is_enable_advance_payment'] = 0;
            if($request->has('is_featured')){
                $services['is_featured'] = 1;
            }
            if($request->has('is_enable_advance_payment')){
                $services['is_enable_advance_payment'] = 1;
            }
            if($request->has('is_slot')){
                $services['is_slot'] = 1;
            }
        }
      
       
        if(!empty($request->advance_payment_amount)){
            $services['advance_payment_amount'] = $request->advance_payment_amount;
        }
        $result = Service::updateOrCreate(['id' => $request->id], $services);

        if($result->providerServiceAddress()->count() > 0)
        {
            $result->providerServiceAddress()->delete();
        }

        if($request->provider_address_id != null) {
            foreach($request->provider_address_id as $address) {
                $provider_service_address = [
                    'service_id'   => $result->id,
                    'provider_address_id'   => $address,
                ];
                $result->providerServiceAddress()->insert($provider_service_address);
            }
        }

        if($request->is('api/*')){
			if($request->has('attachment_count')) {
				for($i = 0 ; $i < $request->attachment_count ; $i++){
					$attachment = "service_attachment_".$i;
					if($request->$attachment != null){
						$file[] = $request->$attachment;
					}
				}
				storeMediaFile($result,$file, 'service_attachment');
			}
		}else{
			storeMediaFile($result,$request->service_attachment, 'service_attachment');
		}

        $message = __('messages.update_form',[ 'form' => __('messages.service') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.service') ] );
		}

        if($request->is('api/*')) {
            $response = [
                'message'=>$message,
                'service_id' => $result->id
            ];
            return comman_custom_response($response);
		}
		return redirect(route('service.index'))->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $auth_user = authSession();
        $tabpage = 'all-plan';
        $providerdata = User::with('providerDocument')->where('user_type', 'provider')->where('id', $id)->first();
        if (empty($providerdata)) {
            $msg = __('messages.not_found_entry', ['name' => __('messages.provider')]);
            return redirect(route('provider.index'))->withError($msg);
        }
        $pageTitle = __('messages.view_form_title', ['form' => __('messages.provider')]);
        
        return view('service.view', compact('pageTitle','providerdata', 'auth_user', 'tabpage'));
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
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $service = Service::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.service')] );
        
        if($service!='') {
            $service->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.service')] );
        }
        if(request()->is('api/*')){
            return comman_custom_response(['message'=> $msg , 'status' => true]);
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
    public function action(Request $request){
        $id = $request->id;
        $service = Service::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.service')] );
        if($request->type === 'restore'){
            $service->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.service')] );
        }

        if($request->type === 'forcedelete'){
            $service->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.service')] );
        }

        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }

}
