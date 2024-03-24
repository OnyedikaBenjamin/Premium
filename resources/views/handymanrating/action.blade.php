
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['handyman-rating.destroy', $handymanrating->id], 'method' => 'delete','data--submit'=>'handymanrating'.$handymanrating->id]) }}
@if(auth()->user()->hasAnyRole(['admin']))
<div class="d-flex justify-content-end align-items-center">
        <a class="mr-2" href="{{ route('handyman-rating.destroy', $handymanrating->id) }}" data--submit="handymanrating{{$handymanrating->id}}" 
            data--confirmation='true' 
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.rating') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.rating') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    </div>
@endif
{{ Form::close() }}