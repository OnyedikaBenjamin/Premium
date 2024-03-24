<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['user.destroy', $user->id], 'method' => 'delete','data--submit'=>'user'.$user->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$user->trashed())
    <a class="mr-2" href="{{ route('user.getchangepassword',['id' => $user->id]) }}" title="{{ __('messages.change_password',['form' => __('messages.user') ]) }}"><i class="fa fa-lock text-success "></i></a>
        <!-- @if($auth_user->can('user view'))
        <a class="mr-2" href="{{ route('user.show',$user->id) }}"><i class="far fa-eye text-secondary"></i></a>
        @endif -->
        @if($auth_user->can('user edit'))
        <a class="mr-2" href="{{ route('user.create',['id' => $user->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.user') ]) }}"><i class="fas fa-pen text-primary "></i></a>
        @endif
        @if($auth_user->can('user delete'))
        <a class="mr-2 text-danger" href="{{ route('user.destroy', $user->id) }}" data--submit="user{{$user->id}}" 
            data--confirmation='true' 
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.user') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.user') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt"></i>
        </a>
        @endif
    @endif
    @if(auth()->user()->hasAnyRole(['admin']) && $user->trashed())
        <a href="{{ route('user.action',['id' => $user->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.user') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.user') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload"
            class=" mr-2">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('user.action',['id' => $user->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.user') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.user') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}