
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['tax.destroy', $tax->id], 'method' => 'delete','data--submit'=>'tax'.$tax->id]) }}
<div class="d-flex justify-content-end align-items-center">

    <a class="mr-3" href="{{ route('tax.destroy', $tax->id) }}" data--submit="tax{{$tax->id}}" 
        data--confirmation='true' 
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.tax') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.tax') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt text-danger"></i>
    </a>
</div>
{{ Form::close() }}