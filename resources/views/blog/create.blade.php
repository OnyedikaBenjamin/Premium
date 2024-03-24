<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('blog list'))
                            <a href="{{ route('blog.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($blogdata,['method' => 'POST','route'=>'blog.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'blog'] ) }}
                        {{ Form::hidden('id') }}
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('title',trans('messages.title').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('title',old('title'),['placeholder' => trans('messages.title'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            @if(auth()->user()->hasAnyRole(['admin','demo_admin']))
                            <div class="form-group col-md-4">
                                {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.author') ]),['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('author_id', [ optional($blogdata->author)->id => optional($blogdata->author)->display_name ], optional($blogdata->author)->id, [
                                            'class' => 'select2js form-group',
                                            'id' => 'author_id',
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.author') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'provider']),
                                        ]) }}
                            </div>
                            @endif
                            <div class="form-group col-md-4">
                                {{ Form::label('status',trans('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'id' => 'role' ,'class' =>'form-control select2js','required']) }}
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-control-label" for="blog_attachment">{{ __('messages.image') }} <span class="text-danger">*</span> </label>
                                <div class="custom-file">
                                    <input type="file" name="blog_attachment[]" class="custom-file-input" data-file-error="{{ __('messages.files_not_allowed') }}" multiple>
                                    <label class="custom-file-label upload-label">{{ __('messages.choose_file',['file' =>  __('messages.attachments') ]) }}</label>
                                </div>
                            </div>
                            <div class="row blog_attachment_div">
                                <div class="col-md-12">
                                    @if(getMediaFileExit($blogdata, 'blog_attachment'))
                                    @php
                                    $attchments = $blogdata->getMedia('blog_attachment');
                                    $file_extention = config('constant.IMAGE_EXTENTIONS');
                                    @endphp
                                    <div class="border-left-2">
                                        <p class="ml-2"><b>{{ __('messages.attached_files') }}</b></p>
                                        <div class="ml-2 my-3">
                                            <div class="row">
                                                @foreach($attchments as $attchment )
                                                <?php
                                                $extention = in_array(strtolower(imageExtention($attchment->getFullUrl())), $file_extention);
                                                ?>

                                                <div class="col-md-2 pr-10 text-center galary file-gallary-{{$blogdata->id}}" data-gallery=".file-gallary-{{$blogdata->id}}" id="blog_attachment_preview_{{$attchment->id}}">
                                                    @if($extention)
                                                    <a id="attachment_files" href="{{ $attchment->getFullUrl() }}" class="list-group-item-action attachment-list" target="_blank">
                                                        <img src="{{ $attchment->getFullUrl() }}" class="attachment-image" alt="">
                                                    </a>
                                                    @else
                                                    <a id="attachment_files" class="video list-group-item-action attachment-list" href="{{ $attchment->getFullUrl() }}">
                                                        <img src="{{ asset('images/file.png') }}" class="attachment-file">
                                                    </a>
                                                    @endif
                                                    <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $attchment->id, 'type' => 'blog_attachment']) }}" data--submit="confirm_form" data--confirmation='true' data--ajax="true" data-toggle="tooltip" title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.attachments") ] ) }}' data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.attachments") ] ) }}' data-message='{{ __("messages.remove_file_msg") }}'>
                                                        <i class="ri-close-circle-line"></i>
                                                    </a>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                {{ Form::label('description',trans('messages.description'), ['class' => 'form-control-label']) }}
                                {{ Form::textarea('description', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    {{ Form::checkbox('is_featured', $blogdata->is_featured, null, ['class' => 'custom-control-input' , 'id' => 'is_featured' ]) }}
                                    <label class="custom-control-label" for="is_featured">{{ __('messages.set_as_featured')  }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        {{ Form::submit( trans('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function preview() {
            blog_image_preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</x-master-layout>