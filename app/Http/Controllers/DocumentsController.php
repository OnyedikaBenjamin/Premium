<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documents;
use App\Models\Service;
use App\Http\Requests\DocumentRequest;
use Yajra\DataTables\DataTables;

class DocumentsController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.document')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('document.index', compact('pageTitle','auth_user','assets','filter'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Documents::query();
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
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="document" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
         
            ->editColumn('name', function($query){                
                if (auth()->user()->can('document edit')) {
                    $link = '<a class="btn-link btn-link-hover" href='.route('document.create', ['id' => $query->id]).'>'.$query->name.'</a>';
                } else {
                    $link = $query->name; 
                }
                return $link;
            })

            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input bg-primary change_status" data-type="document_status" '.($query->status ? "checked" : "").' '.$disabled.'  value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->editColumn('is_required' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input bg-primary change_status" data-type="document_required" data-name="is_required" '.($query->is_required ? "checked" : "").' '.$disabled.'  value="'.$query->id.'" id="f'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="f'.$query->id.'" data-on-label="'.__("messages.yes").'" data-off-label="'.__("messages.no").'"></label>
                    </div>
                </div>';
            })
            ->addColumn('action', function($document){
                return view('document.action',compact('document'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['name','action','status','is_required','check'])
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
                $branches = Documents::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Documents Status Updated';
                break;
            
            case 'change-featured':
                $branches = Documents::whereIn('id', $ids)->update(['is_required' => $request->is_required]);
                $message = 'Bulk Documents Required Updated';
                break;

            case 'delete':
                Documents::whereIn('id', $ids)->delete();
                $message = 'Bulk Documents Deleted';
                break;
                
            case 'restore':
                Documents::whereIn('id', $ids)->restore();
                $message = 'Bulk Documents Restored';
                break;
                
            case 'permanently-delete':
                Documents::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Documents Permanently Deleted';
                break;

            default:
                return response()->json(['status' => false, 'is_required' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'is_required' => true, 'message' => 'Bulk Action Updated']);
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

        $documentdata = Documents::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.document')]);
        
        if( $documentdata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.document')]);
             $documentdata = new Documents;
        }
        
        return view('document.create', compact('pageTitle' ,'documentdata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();

        if(!$request->is('api/*')) {
            $data['is_required'] = 0;
            if($request->has('is_required')){
                $data['is_required'] = 1;
            }
        }
        $result = Documents::updateOrCreate(['id' => $data['id'] ],$data);

        $message = trans('messages.update_form',['form' => trans('messages.document')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.document')]);
        }
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
        return redirect(route('document.index'))->withSuccess($message);        
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
        $document = Documents::find($id);
        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.document')] );
        
        if( $document!='') { 
        
            $document->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.document')] );
        }
        if(request()->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }

    public function action(Request $request){
        $id = $request->id;

        $document  = Documents::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.document')] );
        if($request->type == 'restore') {
            $document->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.document')] );
        }
        if($request->type === 'forcedelete'){
            $document->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.document')] );
        }
        if($request->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
    
}
