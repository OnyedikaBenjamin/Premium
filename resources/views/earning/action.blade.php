<?php
$auth_user= authSession();
?>
{{-- {{ $earningData->id}} --}}
{{-- {{ Form::open(['route' => ['earning.destroy', $row->id], 'method' => 'delete','data--submit'=>'earning'.$row->id]) }}
<div class="d-flex justify-content-end align-items-center">
@if(auth()->user()->hasAnyRole(['admin']))
    <a class="mr-3" href="{{ route('earning.destroy', $row->id) }}" data--submit="earning{{$row->id}}" 
        data--confirmation='true' 
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.earning') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.earning') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt text-danger"></i>
    </a>
@endif
</div>
{{ Form::close() }} --}}