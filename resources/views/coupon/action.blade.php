
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['coupon.destroy', $coupon->id], 'method' => 'delete','data--submit'=>'coupon'.$coupon->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$coupon->trashed())
        <!-- @if($auth_user->can('coupon edit'))
        <a href="{{ route('coupon.create',['id' => $coupon->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.coupon') ]) }}"><i class="fas fa-pen text-primary mr-2"></i></a>
        @endif -->
        @if($auth_user->can('coupon delete'))
        <a class=" mr-3" href="{{ route('coupon.destroy', $coupon->id) }}" data--submit="coupon{{$coupon->id}}" 
            title="{{ __('messages.delete_form_title',['form' => __('messages.coupon') ]) }}"
            data--confirmation='true' 
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.coupon') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.coupon') ]) }}"   
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
        @endif
    @endif

    @if(auth()->user()->hasAnyRole(['admin']) && $coupon->trashed())
        <a href="{{ route('coupon.action',['id' => $coupon->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.coupon') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.coupon') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload"
            class=" mr-2">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('coupon.action',['id' => $coupon->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.coupon') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.coupon') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}