<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Http\Requests\SubCategoryRequest;
use Facade\Ignition\QueryRecorder\Query;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.subcategory')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('subcategory.index',compact('pageTitle','auth_user','assets','filter'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = SubCategory::query()->list();

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

                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="subcategory" onclick="dataTableRowCheck('.$row->id.', this)">';
            })


            ->editColumn('name', function($query){                
                if (auth()->user()->can('subcategory edit')) {
                    $link = '<a class="btn-link btn-link-hover" href='.route('subcategory.create', ['id' => $query->id]).'>'.$query->name.'</a>';
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
            ->editColumn('is_featured' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';

                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input change_status" data-type="subcategory_featured" data-name="is_featured" '.($query->is_featured ? "checked" : "").'  '.  $disabled.' value="'.$query->id.'" id="f'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="f'.$query->id.'" data-on-label="'.__("messages.yes").'" data-off-label="'.__("messages.no").'"></label>
                    </div>
                </div>';
            })
            ->addColumn('action', function ($data) {
                return view('subcategory.action', compact('data'));
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="subcategory_status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })

            ->rawColumns(['action', 'status', 'check','is_featured','name'])
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
                $branches = SubCategory::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Sub Category Status Updated';
                break;
            
            case 'change-featured':
                $branches = SubCategory::whereIn('id', $ids)->update(['is_featured' => $request->is_featured]);
                $message = 'Bulk Sub Category Featured Updated';
                break;

            case 'delete':
                SubCategory::whereIn('id', $ids)->delete();
                $message = 'Bulk Sub Category Deleted';
                break;
                
            case 'restore':
                SubCategory::whereIn('id', $ids)->restore();
                $message = 'Bulk Sub Category Restored';
                break;
                
            case 'permanently-delete':
                SubCategory::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Sub Category Permanently Deleted';
                break;

                default:
                return response()->json(['status' => false,'is_featured' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'is_featured' => true, 'message' => 'Bulk Action Updated']);
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

        $subcategory = SubCategory::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.subcategory')]);
        
        if($subcategory == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.subcategory')]);
            $subcategory = new SubCategory;
        }
        
        return view('subcategory.create', compact('pageTitle' ,'subcategory' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubCategoryRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
       
        $data['is_featured'] = 0;
        if($request->has('is_featured')){
			$data['is_featured'] = 1;
		}
        if(!$request->is('api/*')) {
            if($request->id == null ){
                if(!isset($data['subcategory_image'])){
                    return  redirect()->back()->withErrors(__('validation.required',['attribute' =>'attachments']));
                }
            }
        }
        $result = SubCategory::updateOrCreate(['id' => $data['id'] ],$data);

        storeMediaFile($result,$request->subcategory_image, 'subcategory_image');

        $message = trans('messages.update_form',['form' => trans('messages.subcategory')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.subcategory')]);
        }
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
        return redirect(route('subcategory.index'))->withSuccess($message);    
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
        $subcategory = SubCategory::find($id);
        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.subcategory')] );
        
        if($subcategory!='') { 
            $subcategory->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.subcategory')] );
        }
        if(request()->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
    public function action(Request $request){
        $id = $request->id;

        $subcategory  = SubCategory::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.subcategory')] );
        if($request->type == 'restore') {
            $subcategory->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.subcategory')] );
        }
        if($request->type === 'forcedelete'){
            $subcategory->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.subcategory')] );
        }
        if(request()->is('api/*')){
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
