
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['handymanpayout.destroy', $handymanpayout->id], 'method' => 'delete','data--submit'=>'handymanpayout'.$handymanpayout->id]) }}
<div class="d-flex justify-content-end align-items-center">

    <a class="mr-3" href="{{ route('handymanpayout.destroy', $handymanpayout->id) }}" data--submit="handymanpayout{{$handymanpayout->id}}" 
        data--confirmation='true' 
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.handyman_payout') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.handyman_payout') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt text-danger"></i>
    </a>
</div>
{{ Form::close() }}