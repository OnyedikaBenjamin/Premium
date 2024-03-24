<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($appdata,['method' => 'POST','route'=>'app-download-save', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'category'] ) }}
                            {{ Form::hidden('id') }}
                            <div class="row">
                                <div class="form-group col-md-4">
                                    {{ Form::label('title',trans('messages.title').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('title',old('title'),['placeholder' => trans('messages.title'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('playstore_url',trans('messages.playstore_url').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('playstore_url',old('playstore_url'),['placeholder' => trans('messages.playstore_url'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('appstore_url',trans('messages.appstore_url').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('appstore_url',old('appstore_url'),['placeholder' => trans('messages.appstore_url'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('provider_playstore_url',trans('messages.provider_playstore_url').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('provider_playstore_url',old('provider_playstore_url'),['placeholder' => trans('messages.provider_playstore_url'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('provider_appstore_url',trans('messages.provider_appstore_url').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('provider_appstore_url',old('provider_appstore_url'),['placeholder' => trans('messages.provider_appstore_url'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('description',trans('messages.description'), ['class' => 'form-control-label']) }}
                                    {{ Form::textarea('description', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-control-label" for="app_image">{{ __('messages.image') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="app_image" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label upload-label">{{  __('messages.choose_file',['file' =>  __('messages.image') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                @if(getMediaFileExit($appdata, 'app_image'))
                                    <div class="col-md-2 mb-2">
                                        @php
                                            $extention = imageExtention(getSingleMedia($appdata,'app_image'));
                                        @endphp
                                        <img id="app_image_preview" src="{{getSingleMedia($appdata,'app_image')}}" alt="#" class="attachment-image mt-1" style="background-color:{{ $extention == 'svg' ? $appdata->color : '' }}">
                                            <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $appdata->id, 'type' => 'app_image']) }}"
                                                data--submit="confirm_form"
                                                data--confirmation='true'
                                                data--ajax="true"
                                                title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                                data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                                data-message='{{ __("messages.remove_file_msg") }}'>
                                                <i class="ri-close-circle-line"></i>
                                            </a>
                                    </div>
                                @endif
                                <div class="form-group col-md-4">
                                    <label class="form-control-label" for="app_image_full">{{ __('messages.app_image_full') }} </label>
                                    <div class="custom-file">
                                        <input type="file" name="app_image_full" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label upload-label">{{  __('messages.choose_file',['file' =>  __('messages.app_image_full') ]) }}</label>
                                    </div>
                                    <span class="selected_file"></span>
                                </div>

                                @if(getMediaFileExit($appdata, 'app_image_full'))
                                    <div class="col-md-2 mb-2">
                                        @php
                                            $extention = imageExtention(getSingleMedia($appdata,'app_image_full'));
                                        @endphp
                                        <img id="app_image_full_preview" src="{{getSingleMedia($appdata,'app_image_full')}}" alt="#" class="attachment-image mt-1" style="background-color:{{ $extention == 'svg' ? $appdata->color : '' }}">
                                            <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $appdata->id, 'type' => 'app_image_full']) }}"
                                                data--submit="confirm_form"
                                                data--confirmation='true'
                                                data--ajax="true"
                                                title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                                data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                                data-message='{{ __("messages.remove_file_msg") }}'>
                                                <i class="ri-close-circle-line"></i>
                                            </a>
                                    </div>
                                @endif
                                
                               
                            </div>
                            {{ Form::submit( trans('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>