
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['provideraddress.destroy', $provideraddress->id], 'method' => 'delete','data--submit'=>'providertype'.$provideraddress->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if($auth_user->can('provideraddress delete'))
    <a class="mr-3 text-danger" href="{{ route('provideraddress.destroy', $provideraddress->id) }}" data--submit="providertype{{$provideraddress->id}}" 
        data--confirmation='true' 
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.provider_address') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.address') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt"></i>
    </a>
    @endif
</div>
{{ Form::close() }}