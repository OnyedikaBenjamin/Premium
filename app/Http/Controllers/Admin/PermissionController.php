<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = Permission::orderBy('name','ASC')->whereNull('parent_id')->with('subpermission')->get();
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.permission')  ]);

        $roles = Role::where('status',1)->orderBy('name','ASC');
        if(!\Auth::user()->hasRole('admin')){
            $roles->where('name','!=','admin');
        }
        $roles = $roles->get();

        $auth_user = authSession();

        return view('permission.index',compact(['roles','permission','pageTitle','auth_user']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            return redirect()->route('permission.index')->withError( __('messages.demo_permission_denied') );
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $data = isset($request->permission) ? $request->permission : [];
        $permission_list = Permission::orderBy('name','ASC')->get()->unique('name');
        $roles=Role::whereNotIn('name',['admin'])->get()->map(function($role) use($permission_list){
            $role->revokePermissionTo($permission_list);
        });
        if(count($data)>0){
            foreach ($data as $key => $permission){
                foreach ($permission as $role){
                    $permission = Permission::findOrCreate($key);
                    $guard = Role::findOrCreate($role,'web');
                    $guard->givePermissionTo($permission);
                }
            }
        }
        return redirect()->route('permission.index')->withSuccess(trans('messages.save_form',['form' => trans('messages.permission')]));
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
        //
    }

    public function addPermission($type){
        switch ($type){
            case 'permission' : 
                $title = trans('messages.add_form_title',['form' => trans('messages.permission')  ]);
                break;
            case 'role' : 
                $title = trans('messages.add_form_title',['form' => trans('messages.role')  ]);
                break;
            default : 
                $title = trans('messages.add_form_title',['form' => trans('messages.permission')  ]);
                break;
        }
        return view('permission.add_permission',compact(['title','type']));
    }

    public function savePermission(Request $request){
        if(demoUserPermission()){
            return response()->json(['status'=>false,'message'=>trans('messages.demo_permission_denied'),'event'=>'validation']);
        }
       
        $auth_user = authSession();
        $data = $request->all();
        
        switch ($data['type']){
            case 'permission' :
                    $permission = Permission::create([
                        'name' => $data['name'],
                        'parent_id' => isset($request->parent_id) ? $request->parent_id : null,
                    ]);
                    $admin_role = Role::findByName('admin');
                    $admin_role->givePermissionTo($permission);
                    break;
            case 'role' : 
                    $validator = \Validator::make($data,[
                        'name' => 'required|max:191|regex:/^[\pL\s\-]+$/u|unique:roles,name',
                    ]);
                    
                    if($validator->fails()) {
                        $message = $validator->errors()->first();
                        return response()->json(['status' => false, 'message' => $message, 'event' => 'validation']);
                    }
                    
                    $admin_role = Role::create([
                        'name' => $data['name'] , 
                        'status' => '1' 
                    ]);
                    break;
            default : 
                    return response()->json(['status'=>false,'event' => 'validation' , 'message' => 'Try Again']);
                    break;
        }
        $message = trans('messages.added_permission',['name'=> trans('messages.'.$data['type']) ] );

        return response()->json(['status' => true,'event' => 'refresh' , 'message' => $message]);
    }
}
