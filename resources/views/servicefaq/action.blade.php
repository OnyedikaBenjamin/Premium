
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['servicefaq.destroy', $servicefaq->id], 'method' => 'delete','data--submit'=>'servicefaq'.$servicefaq->id]) }}
<div class="d-flex justify-content-end align-items-center">
        @if($auth_user->can('servicefaq edit'))
        <a class="mr-2" href="{{ route('servicefaq.create',['id' => $servicefaq->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.servicefaq') ]) }}"><i class="fas fa-pen text-secondary"></i></a>
        @endif  

        @if($auth_user->can('servicefaq delete'))
        <a class="mr-2" href="javascript:void(0)" data--submit="servicefaq{{$servicefaq->id}}" 
            data--confirmation='true' data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.servicefaq') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.servicefaq') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
        @endif
</div>
{{ Form::close() }}