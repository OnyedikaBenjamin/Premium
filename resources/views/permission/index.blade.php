<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('permission add'))
                                <a href="{{ route('permission.add',['type'=>'permission']) }}" class="float-right btn btn-sm btn-primary loadRemoteModel"><i class="fa fa-plus-circle"></i> {{ trans('messages.add_form_title',['form' => trans('messages.permission')  ]) }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-md-12">
                {{ Form::open(['route' => 'permission.store','method' => 'post']) }}
                    <div class="accordion cursor" id="permissionList">
                        @foreach($permission as  $key => $data)
                            <?php
                                $a = str_replace("_"," ",$key);
                                $k = ucwords($a);
                            ?>
                            <div class="card mb-2">
                                <div class="card-header d-flex justify-content-between collapsed btn" id="heading_{{$key}}" data-toggle="collapse" data-target="#pr_{{$key}}" aria-expanded="false" aria-controls="pr_{{$key}}">
                                    <div class="header-title">
                                        <h6 class="mb-0 text-capitalize permission-text"> <i class="fa fa-plus mr-10"></i> {{ $data->name }}<span class="badge badge-secondary"></span></h6>
                                    </div>
                                </div>
                                <div id="pr_{{$key}}" class="collapse bg_light_gray" aria-labelledby="heading_{{$key}}" data-parent="#permissionList">
                                    <div class="card-body table-responsive">
                                        <table class="table text-center table-bordered bg_white">
                                            <tr>
                                                <th>{{ trans('messages.name') }}</th>
                                                @foreach($roles as $role)
                                                    <th>{{ ucwords(str_replace('_',' ',$role->name)) }}</th>
                                                @endforeach
                                            </tr>
                                            @foreach($data->subpermission as $p)
                                                <tr>
                                                    <td class="text-capitalize">{{ $p->name }}</td>
                                                    @foreach($roles as $role)
                                                        <td>
                                                            <input class="checkbox no-wh permission_check" id="permission-{{$role->id}}-{{$p->id}}" type="checkbox" name="permission[{{$p->name}}][]" value='{{$role->name}}' {{ (checkRolePermission($role,$p->name)) ? 'checked' : '' }} @if($role->is_hidden) disabled @endif >
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </table>
                                        <input type="submit" name="Save" value="Save" class="btn btn-md btn-primary float-right mall-10">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@section('bottom_script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function(){
                $(document).on('click','#permissionList .card-header',function(){
                    if($(this).find('i').hasClass('fa-minus')){
                        $('#permissionList .card-header i').removeClass('fa-plus').removeClass('fa-minus').addClass('fa-plus');
                        $(this).find('i').addClass('fa-plus').removeClass('fa-minus');
                    }else{
                        $('#permissionList .card-header i').removeClass('fa-plus').removeClass('fa-minus').addClass('fa-plus');
                        $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
</x-master-layout>
