<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderDocument;
use App\Models\Service;
use App\Http\Requests\ProviderDocumentRequest;
use Yajra\DataTables\DataTables;

class ProviderDocumentController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.providerdocument')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('providerdocument.index', compact('pageTitle','auth_user','assets','filter'));
    }

    public function index_data(DataTables $datatable,Request $request)
    {
        
        $query = ProviderDocument::query();
       
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
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" data-type="providerdocument" onclick="dataTableRowCheck('.$row->id.',this)">';
            })
            ->editColumn('is_verified' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                if(auth()->user()->hasAnyRole(['provider','demo_provider'])){
                    if($query->is_verified == 0){
                        $status = '<span class="badge badge-danger">'.__('messages.unverified').'</span>';
                    }else{
                        $status = '<span class="badge badge-success">'.__('messages.verified').'</span>';
                    }
                    return $status;
                }
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input bg-primary change_status" data-type="provider_is_verified" data-name="provider_is_verified" '.($query->is_verified ? "checked" : "").' '.$disabled.'  value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';

            })

            // ->editColumn('provider_id', function($query){                
            //     if (auth()->user()->can('providerdocument edit')) {
            //         $link =  ($query->provider_id != null && isset($query->providers)) ? '<a class="btn-link btn-link-hover" href='.route('providerdocument.create', ['id' => $query->id]).'>'.$query->providers->display_name.'</a>' : '';
            //     } else {
            //         $link = ($query->provider_id != null && isset($query->providers))
            //           ? $query->providers->display_name: '';
            //     }
            //     return $link;
            // })

            ->editColumn('provider_id', function ($query) {
                return view('providerdocument.user', compact('query'));
            })

            // ->editColumn('provider_id' , function ($query){

            //     return ($query->provider_id != null && isset($query->providers)) ? '<a class="btn-link btn-link-hover" href='.route('providerdocument.create', ['id' => $query->id]).'>'.$query->providers->display_name.'</a>' : '';
            // })
            ->editColumn('document_id' , function ($query){
                return ($query->document_id != null && isset($query->document)) ? $query->document->name : '';
            })
            ->filterColumn('provider_id',function($query,$keyword){
                $query->whereHas('providers',function ($q) use($keyword){
                    $q->where('first_name','like','%'.$keyword.'%');
                });
            })
            ->addColumn('action', function($provider_document){
                return view('providerdocument.action',compact('provider_document'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['check','provider_id', 'action','is_verified'])
            ->toJson();
    }

    /* bulck action method */
    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);

        $actionType = $request->action_type;

        $message = 'Bulk Action Updated';

        switch ($actionType) {
            case 'change-featured':
                $branches = ProviderDocument::whereIn('id', $ids)->update(['is_verified' => $request->is_verified]);
                $message = 'Bulk ProviderDocument Featured Updated';
                break;

            case 'delete':
                ProviderDocument::whereIn('id', $ids)->delete();
                $message = 'Bulk Provider Document Deleted';
                break;

            case 'restore':
                ProviderDocument::whereIn('id', $ids)->restore();
                $message = 'Bulk Provider Document Restored';
                break;
                
            case 'permanently-delete':
                ProviderDocument::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Provider Document Permanently Deleted';
                break;    

            default:
                return response()->json(['status' => false,'is_verified' => false, 'message' => 'Action Invalid']);
                break;
        }

        return response()->json(['status' => true, 'is_verified' => true, 'message' => 'Bulk Action Updated']);
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

        $provider_document = ProviderDocument::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.providerdocument')]);
        
        if( $provider_document == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.providerdocument')]);
             $provider_document = new ProviderDocument;
        }
        
        return view('providerdocument.create', compact('pageTitle' ,'provider_document' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderDocumentRequest $request)
    {
        if(demoUserPermission()){
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            } else {
                return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
            }
        }
        $data = $request->all();
        $data['is_verified'] = !empty($data['is_verified']) ? $data['is_verified']: 0;
        $data['provider_id'] = !empty( $data['provider_id'] ) ?  $data['provider_id'] : auth()->user()->id;
        $result = ProviderDocument::updateOrCreate(['id' => $request->id ],$data);
        storeMediaFile($result,$request->provider_document, 'provider_document');

        $message = __('messages.update_form',['form' => __('messages.providerdocument')]);
        if($result->wasRecentlyCreated){
            $message = __('messages.save_form',['form' => __('messages.providerdocument')]);
        }
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
        return redirect(route('providerdocument.index'))->withSuccess($message);        
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
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $provider_document = ProviderDocument::find($id);
        
        if( $provider_document!='') { 
        
            $provider_document->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.providerdocument')] );
        }
        if(request()->is('api/*')){
            return comman_custom_response(['message'=> $msg , 'status' => true]);
        }
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }

    public function action(Request $request){
        $id = $request->id;

        $provider_document  = ProviderDocument::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.providerdocument')] );
        if($request->type == 'restore') {
            $provider_document->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.providerdocument')] );
        }
        if($request->type === 'forcedelete'){
            $provider_document->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.providerdocument')] );
        }
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
    
}
