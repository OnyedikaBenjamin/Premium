<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                            <a href="{{ route('handyman.index') }}" class="float-right btn btn-sm btn-primary"><i
                                    class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @if($auth_user->can('handyman list'))
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($handymandata,['method' => 'POST','route'=>'handyman.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'handyman'] ) }}
                        {{ Form::hidden('id') }}
                        {{ Form::hidden('user_type','handyman') }}
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('first_name',__('messages.first_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('first_name',old('first_name'),['placeholder' => __('messages.first_name'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('last_name',__('messages.last_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('last_name',old('last_name'),['placeholder' => __('messages.last_name'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('username',__('messages.username').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('username',old('username'),['placeholder' => __('messages.username'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('email', __('messages.email').' <span class="text-danger">*</span>', ['class' => 'form-control-label'], false) }}
                                {{ Form::email('email', old('email'), ['placeholder' => __('messages.email'), 'class' => 'form-control', 'required', 'pattern' => '[^@]+@[^@]+\.[a-zA-Z]{2,}', 'title' => 'Please enter a valid email address']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            @if (!isset($handymandata->id) || $handymandata->id == null)
                            <div class="form-group col-md-4">
                                {{ Form::label('password', __('messages.password').' <span class="text-danger">*</span>', ['class' => 'form-control-label'], false) }}
                                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('messages.password'), 'required', 'autocomplete' => 'new-password']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            @endif

                            <div class="form-group col-md-4">
                                {{ Form::label('handymantype_id', __('messages.select_name',[ 'select' => __('messages.handymantype') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('handymantype_id', [optional($handymandata->handymantype)->id => optional($handymandata->handymantype)->name], optional($handymandata->handymantype)->id, [
                                        'class' => 'select2js form-group handymantype',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.handymantype') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'handymantype']),
                                    ]) }}
                            </div>
                            @if(auth()->user()->hasAnyRole(['admin','demo_admin']))
                            <div class="form-group col-md-4">
                                {{ Form::label('provider_id', __('messages.select_name',[ 'select' => __('messages.providers') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('provider_id', [optional($handymandata->providers)->id => optional($handymandata->providers)->display_name], optional($handymandata->providers)->id, [
                                        'class' => 'select2js form-group providers',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.providers') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'provider']),
                                    ]) }}
                            </div>
                            @endif
                            <div class="form-group col-md-4">
                                {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.provider_address') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('service_address_id', [], old('service_address_id'), [
                                        'class' => 'select2js form-group service_address_id',
                                        'id' =>'service_address_id',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.provider_address') ]),
                                    ]) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('country_id', __('messages.select_name',[ 'select' => __('messages.country') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('country_id', [optional($handymandata->country)->id => optional($handymandata->country)->name], optional($handymandata->country)->id, [
                                        'class' => 'select2js form-group country',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.country') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'country']),
                                    ]) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('state_id', __('messages.select_name',[ 'select' => __('messages.state') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('state_id', [], [
                                        'class' => 'select2js form-group state_id',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.state') ]),
                                    ]) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('city_id', __('messages.select_name',[ 'select' => __('messages.city') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('city_id', [], old('city_id'), [
                                        'class' => 'select2js form-group city_id',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.city') ]),
                                    ]) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('contact_number',__('messages.contact_number').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('contact_number',old('contact_number'),['placeholder' => __('messages.contact_number'),'class' =>'form-control contact_number',
                                //'maxlength' => 20, // Maximum 20 characters allowed
                                //'pattern' => '^(\+|-)?\d+$', // Accepts '+' and numeric characters only
                                'required']) }}
                                <small class="help-block with-errors text-danger" id="contact_number_err"></small>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('status',__('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'class' =>'form-control select2js','required']) }}
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-control-label" for="profile_image">{{ __('messages.profile_image') }}
                                </label>
                                <div class="custom-file">
                                    <input type="file" name="profile_image" class="custom-file-input" accept="image/*">
                                    <label
                                        class="custom-file-label upload-label">{{  __('messages.choose_file',['file' =>  __('messages.profile_image') ]) }}</label>
                                </div>
                                <!-- <span class="selected_file"></span> -->
                            </div>

                            @if(getMediaFileExit($handymandata, 'profile_image'))
                            <div class="col-md-2 mb-2">
                                <img id="profile_image_preview" src="{{getSingleMedia($handymandata,'profile_image')}}"
                                    alt="#" class="attachment-image mt-1">
                                <a class="text-danger remove-file"
                                    href="{{ route('remove.file', ['id' => $handymandata->id, 'type' => 'profile_image']) }}"
                                    data--submit="confirm_form" data--confirmation='true' data--ajax="true"
                                    data-toggle="tooltip"
                                    title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                    data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                    data-message='{{ __("messages.remove_file_msg") }}'>
                                    <i class="ri-close-circle-line"></i>
                                </a>
                            </div>
                            @endif

                            <div class="form-group col-md-12">
                                {{ Form::label('address',__('messages.address'), ['class' => 'form-control-label']) }}
                                {{ Form::textarea('address', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.address') ]) }}
                            </div>
                        </div>
                        {{ Form::submit( __('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('bottom_script')
    <script type="text/javascript">
    (function($) {
        "use strict";
        $(document).ready(function() {
            var country_id = "{{ isset($handymandata->country_id) ? $handymandata->country_id : 0 }}";
            var state_id = "{{ isset($handymandata->state_id) ? $handymandata->state_id : 0 }}";
            var city_id = "{{ isset($handymandata->city_id) ? $handymandata->city_id : 0 }}";

            var provider_id = "{{ isset($handymandata->provider_id) ? $handymandata->provider_id : '' }}";
            var service_address_id =
                "{{ isset($handymandata->service_address_id) ? $handymandata->service_address_id : 0 }}";

            stateName(country_id, state_id);
            providerAddress(provider_id, service_address_id)
            $(document).on('change', '#country_id', function() {
                var country = $(this).val();
                $('#state_id').empty();
                $('#city_id').empty();
                stateName(country);
            })
            $(document).on('change', '#state_id', function() {
                var state = $(this).val();
                $('#city_id').empty();
                cityName(state, city_id);
            })
            $(document).on('change', '#provider_id', function() {
                var provider_id = $(this).val();
                $('#service_address_id').empty();
                providerAddress(provider_id, service_address_id);
            })

        })
        $(document).on('keyup', '.contact_number', function() {
            var contactNumberInput = document.getElementById('contact_number');
            var inputValue = contactNumberInput.value;
            inputValue = inputValue.replace(/[^0-9+\- ]/g, '');
            if (inputValue.length > 15) {
                inputValue = inputValue.substring(0, 15);
                $('#contact_number_err').text('Contact number should not exceed 15 characters');
            } else {
                $('#contact_number_err').text('');
            }
            contactNumberInput.value = inputValue;
            if (inputValue.match(/^[0-9+\- ]+$/)) {
                $('#contact_number_err').text('');
            } else {
                $('#contact_number_err').text('Please enter a valid mobile number');
            }
        });


        function stateName(country, state = "") {
            var state_route = "{{ route('ajax-list', [ 'type' => 'state','country_id' =>'']) }}" + country;
            state_route = state_route.replace('amp;', '');

            $.ajax({
                url: state_route,
                success: function(result) {
                    $('#state_id').select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name',['select' => trans('messages.state')]) }}",
                        data: result.results
                    });
                    if (state != null) {
                        $("#state_id").val(state).trigger('change');
                    }
                }
            });
        }

        function cityName(state, city = "") {
            var city_route = "{{ route('ajax-list', [ 'type' => 'city' ,'state_id' =>'']) }}" + state;
            city_route = city_route.replace('amp;', '');

            $.ajax({
                url: city_route,
                success: function(result) {
                    $('#city_id').select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name',['select' => trans('messages.city')]) }}",
                        data: result.results
                    });
                    if (city != null || city != 0) {
                        $("#city_id").val(city).trigger('change');
                    }
                }
            });
        }

        function providerAddress(provider_id, service_address_id = "") {
            var provider_address_route =
                "{{ route('ajax-list', [ 'type' => 'provider_address','provider_id' =>'']) }}" + provider_id;
            provider_address_route = provider_address_route.replace('amp;', '');

            $.ajax({
                url: provider_address_route,
                success: function(result) {
                    $('#service_address_id').select2({
                        width: '100%',
                        placeholder: "{{ trans('messages.select_name',['select' => trans('messages.provider_address')]) }}",
                        data: result.results
                    });
                    if (service_address_id != "") {
                        $('#service_address_id').val(service_address_id).trigger('change');
                    }
                }
            });
        }
    })(jQuery);
    </script>
    @endsection
</x-master-layout>