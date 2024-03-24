
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['ratingreview.destroy', $rating_review->id], 'method' => 'delete','data--submit'=>'rating'.$rating_review->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$rating_review->trashed())
    <a class="mr-2" href="{{ route('ratingreview.create',['id' => $rating_review->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.rating') ]) }}"><i class="fas fa-pen text-secondary"></i></a>
    <a class="mr-2 text-danger" href="javascript:void(0)" data--submit="rating{{$rating_review->id}}" 
        data--confirmation='true' data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.rating') ]) }}"
        title="{{ __('messages.delete_form_title',['form'=>  __('messages.rating') ]) }}"
        data-message='{{ __("messages.delete_msg") }}'>
        <i class="far fa-trash-alt"></i>
    </a>
    @endif
    @if(auth()->user()->hasAnyRole(['admin']) && $rating_review->trashed())
        <a href="{{ route('ratingreview.action',['id' => $rating_review->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.rating') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.document') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('ratingreview.action',['id' => $rating_review->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.document') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.document') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>

{{ Form::close() }}