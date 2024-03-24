
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['providerpayout.destroy', $providerpayout->id], 'method' => 'delete','data--submit'=>'providerpayout'.$providerpayout->id]) }}
<div class="d-flex justify-content-end align-items-center">

    <a class="mr-3" href="{{ route('providerpayout.destroy', $providerpayout->id) }}" data--submit="providerpayout{{$providerpayout->id}}" 
        data--confirmation='true' 
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.provider_payout') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.provider_payout') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt text-danger"></i>
    </a>
</div>
{{ Form::close() }}