
<?php
$auth_user= authSession();
?>
{{ Form::open(['route' => ['service.destroy', $service->id], 'method' => 'delete','data--submit'=>'service'.$service->id]) }}
<div class="d-flex justify-content-end align-items-center">
@if(!$service->trashed())

    @if($auth_user->can('service delete'))
    <a class="mr-2" href="{{ route('service.destroy', $service->id) }}" data--submit="service{{$service->id}}" 
        data--confirmation='true' 
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.service') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.service') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt text-danger"></i>
    </a>
    @endif
    @if(auth()->user()->hasAnyRole(['admin','provider']))
        {{-- <a class="mr-2" href="{{ route('servicefaq.index',['id' => $service->id]) }}" title="{{ __('messages.add_form_title',['form' => __('messages.servicefaq') ]) }}"><i class="fas fa-plus text-primary"></i></a> --}}
    @endif
@endif
@if(auth()->user()->hasAnyRole(['admin']) && $service->trashed())
    <a href="{{ route('service.action',['id' => $service->id, 'type' => 'restore']) }}"
        title="{{ __('messages.restore_form_title',['form' => __('messages.service') ]) }}"
        data--submit="confirm_form"
        data--confirmation='true'
        data--ajax='true'
        data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.service') ]) }}"
        data-message='{{ __("messages.restore_msg") }}'
        data-datatable="reload"
        class="mr-2">
        {{-- <i class="fas fa-redo text-primary"></i> --}}
    </a>
    <a href="{{ route('service.action',['id' => $service->id, 'type' => 'forcedelete']) }}"
        title="{{ __('messages.forcedelete_form_title',['form' => __('messages.service') ]) }}"
        data--submit="confirm_form"
        data--confirmation='true'
        data--ajax='true'
        data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.service') ]) }}"
        data-message='{{ __("messages.forcedelete_msg") }}'
        data-datatable="reload"
        class="mr-2">
        <i class="far fa-trash-alt text-danger"></i>
    </a>
@endif
</div>
{{ Form::close() }}