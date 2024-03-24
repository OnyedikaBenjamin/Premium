
<?php
$auth_user= authSession();
?>
{{ Form::open(['route' => ['booking.destroy', $booking->id], 'method' => 'delete','data--submit'=>'booking'.$booking->id]) }}
<div class="d-flex justify-content-end align-items-center">
@if(!$booking->trashed())
    @if($auth_user->can('booking delete') && !$booking->trashed())
    <a class="mr-3" href="{{ route('booking.destroy', $booking->id) }}" data--submit="booking{{$booking->id}}" 
        data--confirmation='true'
        data--ajax="true"
        data-datatable="reload"
        data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.booking') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.booking') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt text-danger "></i>
    </a>
    @endif
@endif
@if(auth()->user()->hasAnyRole(['admin']) && $booking->trashed())
    <a class="mr-2" href="{{ route('booking.action',['id' => $booking->id, 'type' => 'restore']) }}"
        title="{{ __('messages.restore_form_title',['form' => __('messages.booking') ]) }}"
        data--submit="confirm_form"
        data--confirmation='true'
        data--ajax='true'
        data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.booking') ]) }}"
        data-message='{{ __("messages.restore_msg") }}'
        data-datatable="reload">
        <i class="fas fa-redo text-secondary"></i>
    </a>
    <a href="{{ route('booking.action',['id' => $booking->id, 'type' => 'forcedelete']) }}"
        title="{{ __('messages.forcedelete_form_title',['form' => __('messages.booking') ]) }}"
        data--submit="confirm_form"
        data--confirmation='true'
        data--ajax='true'
        data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.booking') ]) }}"
        data-message='{{ __("messages.forcedelete_msg") }}'
        data-datatable="reload"
        class="mr-2">
        <i class="far fa-trash-alt text-danger"></i>
    </a>
@endif
</div>
{{ Form::close() }}