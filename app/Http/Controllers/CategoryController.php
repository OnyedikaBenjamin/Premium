<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\Booking;
use App\Models\User;
use App\Models\ProviderDocument;
use App\Models\Coupon;
use App\Models\Documents;
use App\Models\Slider;
use App\Models\Blog;
use App\Http\Requests\CategoryRequest;
use App\Models\ProviderType;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;
use League\CommonMark\Node\Block\Document as BlockDocument;

class CategoryController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.category')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('category.index', compact('pageTitle','auth_user','assets','filter'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Category::query()->list();
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
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="category" onclick="dataTableRowCheck('.$row->id.',this)">';
            })

            ->editColumn('name', function($query){                
                if (auth()->user()->can('category edit')) {
                    $link = '<a class="btn-link btn-link-hover" href='.route('category.create', ['id' => $query->id]).'>'.$query->name.'</a>';
                } else {
                    $link = $query->name; 
                }
                return $link;
            })
           
            ->addColumn('action', function ($data) {
                return view('category.action', compact('data'))->render();
            })
            ->editColumn('is_featured' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';

                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="category_featured" data-name="is_featured" '.($query->is_featured ? "checked" : "").'  '.  $disabled.' value="'.$query->id.'" id="f'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="f'.$query->id.'" data-on-label="'.__("messages.yes").'" data-off-label="'.__("messages.no").'"></label>
                    </div>
                </div>';
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="category_status" data-name="status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
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
               
                $branches = Category::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Category Status Updated';
                break;

            case 'change-featured':
                $branches = Category::whereIn('id', $ids)->update(['is_featured' => $request->is_featured]);
                $message = 'Bulk Category Featured Updated';
                break;

            case 'delete':
                Category::whereIn('id', $ids)->delete();
                $message = 'Bulk Category Deleted';
                break;
            
            case 'restore':
                Category::whereIn('id', $ids)->restore();
                $message = 'Bulk Category Restored';
                break;
            
            case 'permanently-delete':
                Category::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Category Permanently Deleted';
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

        $categorydata = Category::find($id);

        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.category')]);
        
        if($categorydata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.category')]);
            $categorydata = new Category;
        }
        
        return view('category.create', compact('pageTitle' ,'categorydata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
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
                if(!isset($data['category_image'])){
                    return  redirect()->back()->withErrors(__('validation.required',['attribute' =>'attachments']));
                }
            }
        }
        $result = Category::updateOrCreate(['id' => $data['id'] ],$data);

        storeMediaFile($result,$request->category_image, 'category_image');

        $message = trans('messages.update_form',['form' => trans('messages.category')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.category')]);
        }
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
        return redirect(route('category.index'))->withSuccess($message);        
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
        $category = Category::find($id);

        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.category')] );
        
        if($category!='') { 

            $service = Service::where('category_id',$id)->first();
        
            $category->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.category')] );
        }
        if(request()->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }   
    public function action(Request $request){
        $id = $request->id;
        $category  = Category::withTrashed()->where('id',$id)->first();
        $msg = __('messages.t_found_entry',['name' => __('messages.category')] );
        if($request->type == 'restore') {
            $category->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.category')] );
        }
        if($request->type === 'forcedelete'){
            $category->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.category')] );
        }
        if(request()->is('api/*')){
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }


    public function check_in_trash(Request $request)
    {
        $ids = $request->ids;
        $type = $request->datatype;

        switch($type){
            case 'category': 
                $InTrash = Category::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'subcategory': 
                $InTrash = SubCategory::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'service': 
                $InTrash = Service::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'servicepackage': 
                $InTrash = ServicePackage::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'booking': 
                $InTrash = Booking::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'user': 
                $InTrash = User::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'providertype': 
                $InTrash = ProviderType::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'providerdocument': 
                $InTrash = ProviderDocument::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'coupon': 
                $InTrash = Coupon::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'slider': 
                $InTrash = Slider::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'document': 
                $InTrash = Documents::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            case 'blog': 
                $InTrash = Blog::withTrashed()->whereIn('id', $ids)->whereNotNull('deleted_at')->get();
            break;
            
            default:
            break;
        }
        
        if (count($InTrash) === count($ids)) {
            return response()->json(['all_in_trash' => true]);
        }

        return response()->json(['all_in_trash' => false]);
    }

    
}
