<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\ServicePackage;
use App\Models\PackageServiceMapping;
use Yajra\DataTables\DataTables;

class ServicePackageController extends Controller
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
        $pageTitle = __('messages.list_form_title',['form' => __('messages.service_package')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('servicepackage.index', compact('pageTitle','auth_user','assets','filter'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = ServicePackage::query();

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query= $query;
        }
        return $datatable->eloquent($query)
        ->addColumn('check', function ($row) {

            return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.',this)">';
        })
        ->editColumn('status' , function ($query){
            return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                <div class="custom-switch-inner">
                    <input type="checkbox" class="custom-control-input  change_status" data-type="servicepackage_status" '.($query->status ? "checked" : "").'  value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                    <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                </div>
            </div>';
        })
            

            ->editColumn('name', function($query){                
                if (auth()->user()->can('service list')) {
                    $link ='<a class="btn-link btn-link-hover"  href='.route('servicepackage.service',$query->id).'>'.$query->name.'</a>';
                } else {
                    $link = $query->name; 
                }
                return $link;
            })

            ->editColumn('category_id', function ($query) {
                return ($query->category_id != null && isset($query->category)) ? $query->category->name : '-';
            })
            ->editColumn('package_type', function ($query) {
                return ($query->package_type != null && isset($query->package_type)) ? ucfirst($query->package_type) : '-';
            })
            ->editColumn('price', function ($query) {
                return ($query->price != null && isset($query->price)) ? getPriceFormat($query->price) : '-';
            })
            ->addColumn('action', function ($servicepackage) {
                return view('servicepackage.action', compact('servicepackage'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status','name','check'])
            ->toJson();
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';

        
        switch ($actionType) {
            case 'change-status':
                $branches = ServicePackage::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Service Status Updated';
                break;

            case 'delete':
                ServicePackage::whereIn('id', $ids)->delete();
                $message = 'Bulk Service Deleted';
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

        $servicepackage = ServicePackage::find($id);   
        $pageTitle = trans('messages.update_form_title', ['form' => trans('messages.package')]);

        if ($servicepackage == null) {
            $pageTitle = trans('messages.add_button_form', ['form' => trans('messages.package')]);
            $servicepackage = new ServicePackage;
        }

        return view('servicepackage.create', compact('pageTitle', 'servicepackage', 'auth_user'));
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
        $provider_id = !empty($request->provider_id) ? $request->provider_id : \Auth::user()->id;
        $service_package = [
            'name' => $request->name,
            'description' => $request->description,
            'provider_id' => $provider_id,
            'status' => $request->status,
            'price' => $request->price,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'package_type' => $request->package_type,
        ];
        if(!$request->is('api/*')) {
            if($request->id == null ){
                if(!isset($data['package_attachment'])){
                    return  redirect()->back()->withErrors(__('validation.required',['attribute' =>'attachments']));
                }
            }
        }
        if(!$request->is('api/*')) {
            $service_package['is_featured'] = 0;
            if($request->has('is_featured')){
                $service_package['is_featured'] = 1;
            }
        }
        $result = ServicePackage::updateOrCreate(['id' => $data['id']], $service_package);
        if ($result->packageServices()->count() > 0) {
            $result->packageServices()->delete();
        }
        if (!empty($request->service_id)) {
            $service = $request->service_id;
            if(!$request->is('api/*')) {
                $service = implode(",", $request->service_id);
            }
            foreach (explode(',', $service) as $key => $value) {
                $mapping_array = [
                    'service_package_id' => $result->id,
                    'service_id' => $value
                ];
                $result->packageServices()->create($mapping_array);
            }
        }
        if ($request->is('api/*')) {
            if ($request->has('attachment_count')) {
                for ($i = 0; $i < $request->attachment_count; $i++) {
                    $attachment = "package_attachment_" . $i;
                    if ($request->$attachment != null) {
                        $file[] = $request->$attachment;
                    }
                }
                storeMediaFile($result, $file, 'package_attachment');
            }
        } else {
            storeMediaFile($result, $request->package_attachment, 'package_attachment');
        }

        $message = trans('messages.update_form', ['form' => trans('messages.package')]);
        if ($result->wasRecentlyCreated) {
            $message = trans('messages.save_form', ['form' => trans('messages.package')]);
        }
        if ($request->is('api/*')) {
            return comman_message_response($message);
        }
        return redirect(route('servicepackage.index'))->withSuccess($message);
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
        if (demoUserPermission()) {
            if (request()->is('api/*')) {
                return comman_message_response(__('messages.demo_permission_denied'));
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $service_package = ServicePackage::find($id);
        $msg = __('messages.msg_fail_to_delete', ['item' => __('messages.package')]);

        if ($service_package != '') {

            $service_package->delete();
            $msg = __('messages.msg_deleted', ['name' => __('messages.package')]);
        }
        if (request()->is('api/*')) {
            return comman_custom_response(['message' => $msg, 'status' => true]);
        }
        return redirect()->back()->withSuccess($msg);
    }

    public function action(Request $request){
        $id = $request->id;
        $servicepackage = ServicePackage::where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.service_package')] );
        if($request->type === 'forcedelete'){
            $servicepackage->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.service_package')] );
        }

        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
