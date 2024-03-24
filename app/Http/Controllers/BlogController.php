<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
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
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.blog')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return view('blog.index', compact('pageTitle','auth_user','assets','filter'));
    }


    public function index_data(DataTables $datatable,Request $request)
    {
        $query = Blog::query();
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
            ->addColumn('check', function ($query) {
                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$query->id.'"  name="datatable_ids[]" value="'.$query->id.'" data-type="blog" onclick="dataTableRowCheck('.$query->id.',this)">';
            })
          
            ->editColumn('title', function($query){                
                if (auth()->user()->can('blog edit')) {
                    $link = '<a class="btn-link btn-link-hover" href='.route('blog.create', ['id' => $query->id]).'>'.$query->title.'</a>';
                } else {
                    $link = $query->title; 
                }
                return $link;
            })



            // ->editColumn('author_id' , function ($query){
            //     return ($query->author_id != null && isset($query->author)) ? $query->author->display_name : '';
            // })
            ->editColumn('author_id' , function ($query){
                return view('blog.user', compact('query'));
            })
            ->filterColumn('author_id',function($query,$keyword){
                $query->whereHas('author',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="category_status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->addColumn('action', function ($blog) {
                return view('blog.action', compact('blog'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['title','action', 'status', 'is_featured','check'])
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
                $branches = Blog::whereIn('id', $ids)->update(['status' => $request->status]);
                $message = 'Bulk Blog Status Updated';
                break;

            case 'delete':
                Blog::whereIn('id', $ids)->delete();
                $message = 'Bulk Blog Deleted';
                break;
                   
            case 'restore':
                Blog::whereIn('id', $ids)->restore();
                $message = 'Bulk Blog Restored';
                break;
                
            case 'permanently-delete':
                Blog::whereIn('id', $ids)->forceDelete();
                $message = 'Bulk Blog Permanently Deleted';
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

        $blogdata = Blog::find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.blog')]);
        
        if($blogdata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.blog')]);
            $blogdata = new Blog;
        }
        
        return view('blog.create', compact('pageTitle' ,'blogdata' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogRequest $request)
    {
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $data = $request->all();
        $data['author_id'] = !empty($request->author_id) ? $request->author_id : auth()->user()->id;
        $data['is_featured'] = 0;
        if($request->has('is_featured')){
			$data['is_featured'] = 1;
		}
        $result = blog::updateOrCreate(['id' => $data['id'] ],$data);

        if($request->is('api/*')){
			if($request->has('attachment_count')) {
				for($i = 0 ; $i < $request->attachment_count ; $i++){
					$attachment = "blog_attachment_".$i;
					if($request->$attachment != null){
						$file[] = $request->$attachment;
					}
				}
				storeMediaFile($result,$file, 'blog_attachment');
			}
		}else{
			storeMediaFile($result,$request->blog_attachment, 'blog_attachment');
		}

        $message = trans('messages.update_form',['form' => trans('messages.blog')]);
        if($result->wasRecentlyCreated){
            $message = trans('messages.save_form',['form' => trans('messages.blog')]);
        }
        if($request->is('api/*')) {
            return comman_message_response($message);
		}
        return redirect(route('blog.index'))->withSuccess($message);        
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
        $blog = Blog::find($id);
        $msg= __('messages.msg_fail_to_delete',['name' => __('messages.blog')] );
        
        if($blog!='') { 

            $blog->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.blog')] );
        }
        if(request()->is('api/*')) {
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg, 'status' => true]);
    }   
    public function action(Request $request){
        $id = $request->id;

        $blog  = Blog::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.blog')] );
        if($request->type == 'restore') {
            $blog->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.blog')] );
        }
        if($request->type === 'forcedelete'){
            $blog->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.blog')] );
        }
        if(request()->is('api/*')){
            return comman_message_response($msg);
		}
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
    
}
