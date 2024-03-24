<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Service;
use App\Http\Requests\SliderRequest;
use Yajra\DataTables\DataTables;
class SliderController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.slider')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('slider.index', compact('pageTitle','auth_user','assets','filter'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Slider::query();
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
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="slider" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
            
            ->editColumn('title', function($query){                
                if (auth()->user()->can('slider edit')) {
                    $link = '<a class="btn-link btn-link-hover" href='.route('slider.create', ['id' => $query->id]).'>'.$query->title.'</a>';
                } else {
                    $link = $query->title; 
                }
                return $link;
            })

            ->editColumn('status' , function ($query){
                $disabled = $query->deleted_at ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input bg-primary change_status" '.$disabled.' data-type="slider_status" '.($query->status ? "checked" : "").' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->editColumn('type_id' , function ($query){
                return ($query->type_id != null && isset($query->service)) ? $query->service->name : '';
            })
            ->filterColumn('type_id',function($query,$keyword){
                $query->whereHas('service',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            })
            ->addColumn('action', function($slider){
                return view('slider.action',compact('slider'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['title','action','status','check'])
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
                $branches = Slider::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Slider Status Updated';
                break;

            case 'delete':
                Slider::whereIn('id', $ids)->delete();
                $message = 'Bulk Slider Deleted';
                break;
                
            case 'restore':
                Slider::whereIn('id', $ids)->restore();
                $message = 'Bulk Slider Restored';
                break;
                
            case 'permanently-delete':
                Slider::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Slider Permanently Deleted';
                break;

            case 'restore':
                Slider::whereIn('id', $ids)->restore();
                $message = 'Bulk Provider Restored';
                break;
                
            case 'permanently-delete':
                Slider::whereIn('id', $ids)->forceDelete();
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

        $sliderdata = Slider::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.slider')]);
        
        if($sliderdata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.slider')]);
            $sliderdata = new Slider;
        }
        
        return view('slider.create', compact('pageTitle' ,'sliderdata' ,'auth_user' ));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $sliders = $request->all();

		$sliders['id']  = $request->id;

        $result = Slider::updateOrCreate(['id' => $request->id], $sliders);
        
        storeMediaFile($result,$request->slider_image, 'slider_image');

        $message = __('messages.update_form',[ 'form' => __('messages.slider') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.slider') ] );
		}
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
		return redirect(route('slider.index'))->withSuccess($message);
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
        $slider = Slider::find($id);
        $msg = __('messages.msg_fail_to_delete',['item' => __('messages.slider')] );
        
        if($slider!='') {        
            $slider->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.slider')] );
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
    public function action(Request $request){
        $id = $request->id;

        $slider  = Slider::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.slider')] );
        if($request->type == 'restore') {
            $slider->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.slider')] );
        }

        if($request->type === 'forcedelete'){
            $slider->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.slider')] );
        }
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
