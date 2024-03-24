<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\BankRequest;
use Yajra\DataTables\DataTables;

class BankController extends Controller
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
        $pageTitle = trans('messages.list_form_title', ['form' => trans('messages.bank')]);
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('bank.index', compact('pageTitle', 'auth_user', 'assets','filter'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Bank::query();
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
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="bank_status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            // ->editColumn('provider_id', function ($query) {
            //     $name = ($query->provider_id != null && isset($query->providers)) ? $query->providers->display_name : '-';

            //     return '<a class="btn-link btn-link-hover" href='.route('provider.show', $query->providers->id).'>'.$name.'</a>';

            // })
            ->editColumn('provider_id', function ($query) {
                return view('bank.user', compact('query'));
            })
            ->filterColumn('provider_id',function($query,$keyword){
                $query->whereHas('providers',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->addColumn('action', function ($bank) {
                return view('bank.action', compact('bank'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['check','action', 'status', 'is_featured','provider_id'])
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
                $branches = Bank::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Bank List Status Updated';
                break;

            case 'delete':
                Bank::whereIn('id', $ids)->delete();
                $message = 'Bulk Bank List Deleted';
                break;

            case 'restore':
                Bank::whereIn('id', $ids)->restore();
                $message = 'Bulk Bank Restored';
                break;
                
            case 'permanently-delete':
                Bank::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Bank Permanently Deleted';
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

        $bankdata = Bank::find($id);
     
        $pageTitle = trans('messages.update_form_title', ['form' => trans('messages.bank')]);

        if ($bankdata == null) {
            $pageTitle = trans('messages.add_button_form', ['form' => trans('messages.bank')]);
            $bankdata = new Bank();
        }

        return view('bank.create', compact('pageTitle', 'bankdata', 'auth_user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\BankRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
        if (!$request->is('api/*')) {
            if ($request->id == null) {
                if (!isset($data['bank_attachment'])) {
                    return  redirect()->back()->withErrors(__('validation.required', ['attribute' => 'attachments']));
                }
            }
        }
        $result = Bank::updateOrCreate(['id' => $data['id']], $data);
        if ($request->is('api/*')) {
            if ($request->has('attachment_count')) {
                for ($i = 0; $i < $request->attachment_count; $i++) {
                    $attachment = "bank_attachment_" . $i;
                    if ($request->$attachment != null) {
                        $file[] = $request->$attachment;
                    }
                }
                storeMediaFile($result, $file, 'bank_attachment');
            }
        } else {
            storeMediaFile($result, $request->bank_attachment, 'bank_attachment');
        }
        $message = trans('messages.update_form', ['form' => trans('messages.bank')]);
        if ($result->wasRecentlyCreated) {
            $message = trans('messages.save_form', ['form' => trans('messages.bank')]);
        }
        if($request->has('provider_id')){
            return redirect(route('providerpayout.create',$data['provider_id']))->withSuccess($message);
        }else{
            return redirect(route('bank.index'))->withSuccess($message);
        }
        
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
        $providerdata = User::with('providerbank')->where('user_type','provider')->where('id',$id)->first();
        if(empty($providerdata))
        {
            $msg = __('messages.not_found_entry',['name' => __('messages.provider')] );
            return redirect(route('provider.index'))->withError($msg);
        }
        $pageTitle = __('messages.view_form_title',['form'=> __('messages.provider')]);
        return view('bank.view', compact('pageTitle' ,'providerdata' ,'auth_user' ));
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
        $bank = Bank::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.bank')] );
        
        if($bank!='') {
            $bank->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.bank')] );
        }
        if(request()->is('api/*')){
            return comman_custom_response(['message'=> $msg , 'status' => true]);
        }
        return redirect()->back()->withSuccess($msg);
    }
    public function action(Request $request){
        $id = $request->id;
        $bank = bank::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.bank')] );
        if($request->type === 'restore'){
            $bank->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.bank')] );
        }

        if($request->type === 'forcedelete'){
            $bank->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.bank')] );
        }

        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
