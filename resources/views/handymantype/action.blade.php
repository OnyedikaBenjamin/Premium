
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['handymantype.destroy', $handymantype->id], 'method' => 'delete','data--submit'=>'handymantype'.$handymantype->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$handymantype->trashed())
        @if(auth()->user()->hasRole(['provider','admin']) )
            <a class="mr-3 text-danger" href="{{ route('handymantype.destroy', $handymantype->id) }}" data--submit="handymantype{{$handymantype->id}}" 
                data--confirmation='true' 
                data--ajax="true"
                data-datatable="reload"
                data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.handymantype') ]) }}"
                title="{{ __('messages.delete_form_title',['form'=>  __('messages.handymantype') ]) }}"
                data-message='{{ __("messages.delete_msg") }}'>
                <i class="far fa-trash-alt"></i>
            </a>
        @endif
    @endif
    @if(auth()->user()->hasAnyRole(['provider','admin']) && $handymantype->trashed())
        <a class="mr-2" href="{{ route('handymantype.action',['id' => $handymantype->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.handymantype') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.handymantype') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('handymantype.action',['id' => $handymantype->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.handymantype') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.handymantype') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}