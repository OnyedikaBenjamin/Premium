
<?php
    $auth_user= authSession();
?>
{{ Form::open(['route' => ['blog.destroy', $blog->id], 'method' => 'delete','data--submit'=>'blog'.$blog->id]) }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$blog->trashed())
        <!-- @if($auth_user->can('blog edit'))
        <a class="mr-2" href="{{ route('blog.create',['id' => $blog->id]) }}" title="{{ __('messages.update_form_title',['form' => __('messages.blog') ]) }}"><i class="fas fa-pen text-primary"></i></a>
        @endif -->

        @if($auth_user->can('blog delete'))
        <a class="mr-3" href="{{ route('blog.destroy', $blog->id) }}" data--submit="blog{{$blog->id}}" 
            data--confirmation='true'
            data--ajax="true" 
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.blog') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.blog') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
        @endif
    @endif
    @if(auth()->user()->hasAnyRole(['admin']) && $blog->trashed())
        <a href="{{ route('blog.action',['id' => $blog->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.blog') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.blog') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('blog.action',['id' => $blog->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.blog') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.blog') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload"
            class="mr-2">
            <i class="far fa-trash-alt text-danger"></i>
        </a>
    @endif
</div>
{{ Form::close() }}