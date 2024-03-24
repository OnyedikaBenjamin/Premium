<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingHandymanMapping;
use App\Http\Requests\UserRequest;
use Yajra\DataTables\DataTables;
use Hash;

class HandymanController extends Controller
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
        $pageTitle = __('messages.list_form_title',['form' => __('messages.handyman')] );
        if($request->status == 'pending'){
            $pageTitle = __('messages.pending_list_form_title',['form' => __('messages.handyman')] );
        }
        if($request->status == 'unassigned'){
            $pageTitle = __('messages.unassigned_list_form_title',['form' => __('messages.handyman')] );
        }
        $auth_user = authSession();
        $assets = ['datatable'];
        $list_status = $request->status;
        return view('handyman.index', compact('list_status','pageTitle','auth_user','assets','filter'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = User::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        $query = $query->where('user_type','handyman');
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->withTrashed();
        }
        if(auth()->user()->hasRole('provider')) {
            $query->where('provider_id', auth()->user()->id);
        }
        if($request->list_status == null){
            $query = $query->where('status',1)->whereNotNull('provider_id');
        }
        if($request->list_status == 'pending'){
            $query = $query->where('status',0);
        }
        if($request->list_status == 'unassigned'){
            $query = $query->where('status',0)->orWhere('provider_id',NULL)->where('user_type','handyman');
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="user" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
         

            // ->editColumn('display_name', function($query){                
            //     if (auth()->user()->can('handyman edit')) {
            //         $link ='<a class="btn-link btn-link-hover" href='.route('handyman.create', ['id' => $query->id]).'>'.$query->display_name.'</a>';
            //     } else {
            //         $link = $query->display_name; 
            //     }
            //     return $link;
            // })

            ->editColumn('display_name', function ($query) {
                return view('handyman.user', compact('query'));
            })



            ->editColumn('status', function($query) {
                if($query->status == 0){
                    $status = '<a class="btn-sm text-white btn-success"  href='.route('handyman.approve',$query->id).'>Accept</a>';
                }else{
                    $status = '<span class="badge badge-active">'.__('messages.active').'</span>';
                }
                return $status;
            })
        //     ->editColumn('provider_id', function($handyman) {
        //         return $handyman->provider_id != null && isset($handyman->providers) ? $handyman->providers->display_name : '-';
            
        //    })
            ->editColumn('provider_id', function($query) {
            return view('handyman.provider', compact('query')); 
            })
            ->editColumn('address', function($query) {
                return ($query->address != null && isset($query->address)) ? $query->address : '-';
            })
            
            ->filterColumn('provider_id',function($qry,$keyword){
                $qry->whereHas('providers',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })

            ->addColumn('action', function($handyman){
                return view('handyman.action',compact('handyman'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['check','display_name','action','status'])
            ->toJson();
    }

    /* bulck action method */
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';

        // dd($actionType, $ids, $request->status);
        switch ($actionType) {
            case 'change-status':
                $branches = User::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Handyman Status Updated';
                break;

            case 'delete':
                User::whereIn('id', $ids)->delete();
                $message = 'Bulk Handyman Deleted';
                break;

            case 'restore':
                User::whereIn('id', $ids)->restore();
                $message = 'Bulk Handyman Restored';
                break;
                
            case 'permanently-delete':
                User::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Handyman Permanently Deleted';
                break;

            case 'restore':
                User::whereIn('id', $ids)->restore();
                $message = 'Bulk Provider Restored';
                break;
                
            case 'permanently-delete':
                User::whereIn('id', $ids)->forceDelete();
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

        $handymandata = User::find($id);
        $pageTitle = __('messages.update_form_title',['form'=> __('messages.handyman')]);
        
        if($handymandata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.handyman')]);
            $handymandata = new User;
        }
        
        return view('handyman.create', compact('pageTitle' ,'handymandata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
        if(auth()->user()->hasAnyRole(['provider'])){
            $auth_user = authSession();
            $user_id = $auth_user->id;
            $data['provider_id'] = $user_id;
        }
        if($request->id == null && default_earning_type() === 'subscription'){
            $exceed =  get_provider_plan_limit($data['provider_id'],'handyman');
            if(!empty($exceed)){
                if($exceed == 1){
                    $message = __('messages.limit_exceed',['name'=>__('messages.handyman')]);
                }else{
                    $message = __('messages.not_in_plan',['name'=>__('messages.handyman')]);
                }
                if($request->is('api/*')){
                    return comman_message_response($message);
                }else{
                    return  redirect()->back()->withErrors($message);
                }
            }
         }
        $id = $data['id'];

        $data['user_type'] = $data['user_type'] ?? 'handyman';
        $data['is_featured'] = 0;
        
        if($request->has('is_featured')){
			$data['is_featured'] = 1;
		}

        $data['display_name'] = $data['first_name']." ".$data['last_name'];
        // Save User data...
        if($id == null){
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
        }else{
            $user = User::findOrFail($id);
            // User data...
            // $user->removeRole($user->user_type);
            $user->fill($data)->update();
        }
        if($data['status'] == 1 && auth()->user()->hasAnyRole(['admin'])){
            \Mail::send('verification.verification_email',
            array(), function($message) use ($user)
            {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->to($user->email);
            });
        }
        $user->assignRole($data['user_type']);
        storeMediaFile($user,$request->profile_image, 'profile_image');
        $message = __('messages.update_form',[ 'form' => __('messages.handyman') ] );
		if($user->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.handyman') ] );
		}

        if($request->is('api/*')) {
            return comman_message_response($message);
		}

		return redirect(route('handyman.index'))->withSuccess($message);
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
        $providerdata = User::with('providerHandyman')->where('user_type','provider')->where('id',$id)->first();        
        if(empty($providerdata))
        {
            $msg = __('messages.not_found_entry',['name' => __('messages.provider')] );
            return redirect(route('provider.index'))->withError($msg);
        }
        $pageTitle = __('messages.view_form_title',['form'=> __('messages.provider')]);        
        return view('handyman.view', compact('pageTitle' ,'providerdata' ,'auth_user' ));
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
        $handyman = User::find($id);
        $msg = __('messages.msg_fail_to_delete',['item' => __('messages.handyman')] );
        
        if($handyman!='') { 
            $handyman->delete();
            $msg = __('messages.msg_deleted',['name' => __('messages.handyman')] );
        }
        if(request()->is('api/*')){
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
    public function action(Request $request){
        $id = $request->id;

        $user  = User::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.handyman')] );
        if($request->type == 'restore') {
            $user->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.handyman')] );
        }
        if($request->type === 'forcedelete'){
            $user->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.handyman')] );
        }
        if(request()->is('api/*')){
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }

    public function approve($id){
        $provider = User::find($id);
        $provider->status = 1;
        $provider->save();
        $msg = __('messages.approve_successfully');
        return redirect()->back()->withSuccess($msg);
    }

    public function updateProvider(Request $request)
    {
        $id = $request->id;
        $handyman = User::with('handyman')->findOrFail($id);
        $provider_id = $request->provider_id;

        $handyman->update(['provider_id' => $provider_id]);         

        return response()->json(['message' => 'Provider Assign Successfully', 'status' => true]);
    }



    public function getChangePassword(Request $request){
        $id = $request->id;
        $auth_user = authSession();

        $handymandata = User::find($id);
        $pageTitle = __('messages.change_password',['form'=> __('messages.change_password')]);
        if($handymandata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.handyman')]);
            $handymandata = new User;
        }
        return view('handyman.changepassword', compact('pageTitle' ,'handymandata' ,'auth_user'));
    }

    public function changePassword(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $user = User::where('id', $request->id)->first();
        if ($user == "") {
            $message = __('messages.user_not_found');
            return comman_message_response($message, 400);
        }

        $validator = \Validator::make($request->all(), [
            'old' => 'required|min:8|max:255',
            'password' => 'required|min:8|confirmed|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('handyman.changepassword',['id' => $user->id])->with('errors', $validator->errors());
        }

        $hashedPassword = $user->password;

        $match = Hash::check($request->old, $hashedPassword);

        $same_exits = Hash::check($request->password, $hashedPassword);
        if ($match) {
            if ($same_exits) {
                $message = __('messages.old_new_pass_same');
                return redirect()->route('handyman.changepassword',['id' => $user->id])->with('error', $message);
            }

            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            $message = __('messages.password_change');
            return redirect()->route('handyman.index')->withSuccess($message);
        } else {
            $message = __('messages.valid_password');
            return redirect()->route('handyman.changepassword',['id' => $user->id])->with('error', $message);
        }
    }
}
