<?php

namespace App\Http\Controllers;
use App\Models\ServiceFaq;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ServiceFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.servicefaq')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        $service_id = request()->id;
        return view('servicefaq.index', compact('pageTitle','auth_user','assets','service_id'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {

        $query = ServiceFaq::where('service_id',$request->service_id);
        
        
        return $datatable ->eloquent($query)
        ->addColumn('check', function ($row) {
            return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="category" onclick="dataTableRowCheck('.$row->id.',this)">';
        })
        ->editColumn('status' , function ($servicefaq){
            return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                <div class="custom-switch-inner">
                    <input type="checkbox" class="custom-control-input bg-success change_status" data-type="servicefaq_status" '.($servicefaq->status ? "checked" : "").'  value="'.$servicefaq->id.'" id="'.$servicefaq->id.'" data-id="'.$servicefaq->id.'">
                    <label class="custom-control-label" for="'.$servicefaq->id.'" data-on-label="" data-off-label=""></label>
                </div>
            </div>';
        })
        ->editColumn('service_id' , function ($servicefaq){
            return optional($servicefaq->service)->name;
        })
        ->addColumn('action', function($servicefaq){
            return view('servicefaq.action',compact('servicefaq'))->render();
        })
        ->addIndexColumn()
        ->rawColumns(['check','action','status'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $service_id = $request->service_id;
        $auth_user = authSession();
        $servicefaq = ServiceFaq::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.servicefaq')]);
        
        if($servicefaq == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.servicefaq')]);
            $servicefaq = new ServiceFaq;
        }
        
        return view('servicefaq.create', compact('pageTitle' ,'servicefaq' ,'auth_user','service_id' ));
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
        $data = $request->all();
        $result = ServiceFaq::updateOrCreate(['id' => $data['id'] ],$data);
        $message = trans('messages.update_form',['form' => trans('messages.servicefaq')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.servicefaq')]);
        }

        return redirect(route('servicefaq.index',['id'=>$data['service_id']]))->withSuccess($message);   
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
        $servicefaq = ServiceFaq::find($id);
        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.servicefaq')] );
        
        if($servicefaq!='') { 

            $servicefaq->delete();
        
            $msg= __('messages.msg_deleted',['name' => __('messages.servicefaq')] );
        }

        return redirect()->back()->withSuccess($msg);
    }
}