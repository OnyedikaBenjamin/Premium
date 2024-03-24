<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('bank list'))
                            <a href="{{ route('bank.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($bankdata,['method' => 'POST','route'=>'bank.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'bank'] ) }}
                        {{ Form::hidden('id') }}
                        <div class="row">
                            @if(auth()->user()->hasAnyRole(['admin','demo_admin']))
                            <div class="form-group col-md-4">
                                {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.user') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('provider_id', [ optional($bankdata->providers)->id => optional($bankdata->providers)->display_name ], optional($bankdata->providers)->id, [
                                            'class' => 'select2js form-group',
                                            'id' => 'provider_id',
                                            'required',
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.user') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'all_user']),
                                        ]) }}
                            </div>
                            @endif
                            <div class="form-group col-md-4">
                                {{ Form::label('bank_name',trans('messages.bank_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('bank_name',old('bank_name'),['placeholder' => trans('messages.bank_name'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('branch_name',trans('messages.branch_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('branch_name',old('branch_name'),['placeholder' => trans('messages.branch_name'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('account_no',trans('messages.account_no').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('account_no',old('account_no'),['placeholder' => trans('messages.account_no'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('ifsc_no',trans('messages.ifsc_no').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('ifsc_no',old('ifsc_no'),['placeholder' => trans('messages.ifsc_no'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('mobile_no',trans('messages.mobile_no').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('mobile_no',old('mobile_no'),['placeholder' => trans('messages.mobile_no'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('aadhar_no',trans('messages.aadhar_no').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('aadhar_no',old('aadhar_no'),['placeholder' => trans('messages.aadhar_no'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('pan_no',trans('messages.pan_no').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('pan_no',old('pan_no'),['placeholder' => trans('messages.pan_no'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('status',trans('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'id' => 'role' ,'class' =>'form-control select2js','required']) }}
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-control-label" for="bank_attachment">{{ __('messages.image') }} <span class="text-danger">*</span> </label>
                                <div class="custom-file">
                                    <input type="file" name="bank_attachment[]" class="custom-file-input" data-file-error="{{ __('messages.files_not_allowed') }}" multiple>
                                    <label class="custom-file-label upload-label">{{ __('messages.choose_file',['file' =>  __('messages.attachments') ]) }}</label>
                                </div>
                            </div>
                            <div class="row bank_attachment_div">
                                <div class="col-md-12">
                                    @if(getMediaFileExit($bankdata, 'bank_attachment'))
                                    @php
                                    $attchments = $bankdata->getMedia('bank_attachment');
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

                                                <div class="col-md-2 pr-10 text-center galary file-gallary-{{$bankdata->id}}" data-gallery=".file-gallary-{{$bankdata->id}}" id="bank_attachment_preview_{{$attchment->id}}">
                                                    @if($extention)
                                                    <a id="attachment_files" href="{{ $attchment->getFullUrl() }}" class="list-group-item-action attachment-list" target="_blank">
                                                        <img src="{{ $attchment->getFullUrl() }}" class="attachment-image" alt="">
                                                    </a>
                                                    @else
                                                    <a id="attachment_files" class="video list-group-item-action attachment-list" href="{{ $attchment->getFullUrl() }}">
                                                        <img src="{{ asset('images/file.png') }}" class="attachment-file">
                                                    </a>
                                                    @endif
                                                    <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $attchment->id, 'type' => 'bank_attachment']) }}" data--submit="confirm_form" data--confirmation='true' data--ajax="true" data-toggle="tooltip" title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.attachments") ] ) }}' data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.attachments") ] ) }}' data-message='{{ __("messages.remove_file_msg") }}'>
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
            bank_image_preview.src = URL.createObjectURL(event.target.files[0]);
        }
        $(document).on('keyup', '.mobile_no', function() {
            var contactNumberInput = document.getElementById('mobile_no');
            var inputValue = contactNumberInput.value;
            inputValue = inputValue.replace(/[^0-9+\- ]/g, '');
            if (inputValue.length > 15) {
                inputValue = inputValue.substring(0, 15);
                $('#mobile_no_err').text('Contact number should not exceed 15 characters');
            } else {
                    $('#mobile_no_err').text('');
            }
            contactNumberInput.value = inputValue;
            if (inputValue.match(/^[0-9+\- ]+$/)) {
                $('#mobile_no_err').text('');
            } else {
                $('#mobile_no_err').text('Please enter a valid mobile number');
            }
        });
    </script>
</x-master-layout>