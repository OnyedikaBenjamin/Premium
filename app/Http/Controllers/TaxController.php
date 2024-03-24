<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use Yajra\DataTables\DataTables;


class TaxController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.tax')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('taxes.index', compact('pageTitle','auth_user','assets','filter'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Tax::query();
        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if (auth()->user()->hasAnyRole(['admin'])) {
            $query->newquery();
        }
        
        return $datatable->eloquent($query)
        ->addColumn('check', function ($row) {
            return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
        })
     
        ->editColumn('title', function($query){                
            if (auth()->user()->can('tax edit')) {

                $link = '<a class="btn-link btn-link-hover" href='.route('tax.create', ['id' => $query->id]).'>'.$query->title.'</a>';
            } else {
                $link = $query->title; 
            }
            return $link;
        })



        ->editColumn('status' , function ($query){
            return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                <div class="custom-switch-inner">
                    <input type="checkbox" class="custom-control-input  change_status" data-type="tax_status" '.($query->status ? "checked" : "").'  value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                    <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                </div>
            </div>';
        })
        ->editColumn('value' , function ($query){
            $value = getPriceFormat($query->value);
            if($query->type === 'percent'){
                $value = $query->value. '%';
            }
            return $value;
        })
        ->addColumn('action', function($tax){
            return view('taxes.action',compact('tax'))->render();
        })
        ->addIndexColumn()
        ->rawColumns(['check','title','action','status'])
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
                $branches = Tax::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Tax Status Updated';
                break;

            case 'delete':
                Tax::whereIn('id', $ids)->delete();
                $message = 'Bulk Tax Deleted';
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

        $taxdata = Tax::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.tax')]);
        
        if($taxdata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.tax')]);
            $taxdata = new Tax;
        }
        
        return view('taxes.create', compact('pageTitle' ,'taxdata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
       
        $result = Tax::updateOrCreate(['id' => $data['id'] ],$data);


        $message = trans('messages.update_form',['form' => trans('messages.tax')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.tax')]);
        }

        if($request->is('api/*')) {
            return comman_message_response($message);
		}

        return redirect(route('tax.index'))->withSuccess($message);        
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
        $tax = Tax::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.tax')] );
        
        if($tax != '') { 
            $tax->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.tax')] );
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }
}
