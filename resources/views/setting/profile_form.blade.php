<div class="col-md-12">
    <div class="row ">
        <div class="col-md-3">
            <div class="user-sidebar">
                <div class="user-body user-profile text-center">
                    <div class="user-img">
                        <img class="rounded-circle avatar-100 image-fluid profile_image_preview"
                            src="{{ getSingleMedia($user_data,'profile_image', null) }}" alt="profile-pic">
                    </div>
                    <div class="sideuser-info">
                        <span class="mb-2">{{ $user_data->display_name }}</span>
                        <!-- <a>{{ $user_data->email }}</a> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="user-content">
                {{ Form::model($user_data, ['route'=>'updateProfile','method' => 'POST','data-toggle'=>"validator" , 'enctype'=> 'multipart/form-data','id' => 'user-form']) }}
                <input type="hidden" name="profile" value="profile">
                {{ Form::hidden('username') }}
                {{ Form::hidden('email') }}
                {{ Form::hidden('id', null, array('placeholder' => 'id','class' => 'form-control')) }}
                <div class="row ">

                    <div class="form-group col-md-6">
                        {{ Form::label('first_name',__('messages.first_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                        {{ Form::text('first_name',old('first_name'),['placeholder' => __('messages.first_name'),'class' =>'form-control','required']) }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('last_name',__('messages.last_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                        {{ Form::text('last_name',old('last_name'),['placeholder' => __('messages.last_name'),'class' =>'form-control','required']) }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('username',__('messages.username').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                        {{ Form::text('username',old('username'),['placeholder' => __('messages.username'),'class' =>'form-control','required']) }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>
                    @if(auth()->user()->hasRole('provider'))
                    <div class="form-group col-md-6">
                        {{ Form::label('designation',__('messages.designation').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                        {{ Form::text('designation',old('designation'),['placeholder' => __('messages.designation'),'class' =>'form-control','required']) }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>
                    @endif
                    <div class="form-group col-md-6">
                        {{ Form::label('country_id', __('messages.select_name',[ 'select' => __('messages.country') ]),['class'=>'form-control-label'],false) }}
                        <br />
                        {{ Form::select('country_id', [optional($user_data->country)->id => optional($user_data->country)->name], optional($user_data->country)->id, [
								'class' => 'select2js form-group country',
								'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.country') ]),
								'data-ajax--url' => route('ajax-list', ['type' => 'country']),
							]) }}
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('state_id', __('messages.select_name',[ 'select' => __('messages.state') ]),['class'=>'form-control-label'],false) }}
                        <br />
                        {{ Form::select('state_id', [], [
								'class' => 'select2js form-group state_id',
								'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.state') ]),
							]) }}
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('city_id', __('messages.select_name',[ 'select' => __('messages.city') ]),['class'=>'form-control-label'],false) }}
                        <br />
                        {{ Form::select('city_id', [], old('city_id'), [
								'class' => 'select2js form-group city_id',
								'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.city') ]),
							]) }}
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('email', __('messages.email').' <span class="text-danger">*</span>', ['class' => 'form-control-label'], false) }}
                        {{ Form::email('email', old('email'), ['placeholder' => __('messages.email'), 'class' => 'form-control', 'required', 'pattern' => '[^@]+@[^@]+\.[a-zA-Z]{2,}', 'title' => 'Please enter a valid email address']) }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('contact_number', __('messages.contact_number').' <span class="text-danger">*</span>', ['class' => 'form-control-label'], false) }}
                        {{ Form::text('contact_number', old('contact_number'), ['placeholder' => __('messages.contact_number'), 'class' => 'form-control contact_number', 'required']) }}
                        <small class="help-block with-errors text-danger " id="contact_number_err"></small>
                    </div>

                    @if(auth()->user()->hasRole('handyman'))

                    <div class="form-group col-md-6">
                        {{ Form::label('handymantype_id', __('messages.select_name',[ 'select' => __('messages.handymantype') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                        <br />
                        {{ Form::select('handymantype_id', [optional($user_data->handymantype)->id => optional($user_data->handymantype)->name], optional($user_data->handymantype)->id, [
                                        'class' => 'select2js form-group handymantype',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.handymantype') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'handymantype']),
                                    ]) }}
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.provider_address') ]),['class'=>'form-control-label'],false) }}
                        <br />
                        {{ Form::select('service_address_id',[ optional($user_data->handymanAddressMapping)->id => optional($user_data->handymanAddressMapping)->address ],$user_data->service_address_id,[
									'class' => 'select2js form-group service_address_id',
									'id' =>'service_address_id',
									'data-ajax--url' => route('ajax-list', ['type' => 'provider_address' , 'provider_id' => $user_data->provider_id ]),
									'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.provider_address') ]),
								]) }}
                    </div>
                    @endif

                    <div class="form-group col-md-6">
                        {{ Form::label('status',__('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                        {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'class' =>'form-control select2js','required']) }}
                    </div>

                    <div class="form-group col-md-6">
                        {{ Form::label('profile_image',__('messages.choose_profile_image'),['class'=>'form-control-label '] ) }}
                        <div class="custom-file">
                            {{ Form::file('profile_image', ['class'=>"custom-file-input custom-file-input-sm detail" , 'id'=>"profile_image" , 'lang'=>"en" , 'accept'=>"image/*"]) }}
                            <label class="custom-file-label upload-label" id="imagelabel"
                                for="profile_image">{{ __('messages.profile_image') }}</label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {{ Form::label('address',__('messages.address'), ['class' => 'form-control-label']) }}
                        {{ Form::textarea('address', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.address') ]) }}
                    </div>
                    <div class="col-md-12">
                        {{ Form::submit(__('messages.update'), ['class'=>"btn btn-md btn-primary float-md-right"]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// (function($) {
// 	"use strict";
$(document).ready(function() {
    $('.select2js').select2({
        width: '100%',
        // dropdownParent: $(this).parent()
    });
    var country_id = "{{ isset($user_data->country_id) ? $user_data->country_id : 0 }}";
    var state_id = "{{ isset($user_data->state_id) ? $user_data->state_id : 0 }}";
    var city_id = "{{ isset($user_data->city_id) ? $user_data->city_id : 0 }}";

    stateName(country_id, state_id);
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
    $(document).on('change', '#profile_image', function() {
        readURL(this);
    })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            var res = isImage(input.files[0].name);

            if (res == false) {
                var msg = "{{ __('messages.image_png_gif') }}";
                Snackbar.show({
                    text: msg,
                    pos: 'bottom-center',
                    backgroundColor: '#d32f2f',
                    actionTextColor: '#fff'
                });
                return false;
            }

            reader.onload = function(e) {
                $('.profile_image_preview').attr('src', e.target.result);
                $("#imagelabel").text((input.files[0].name));
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(document).ready(function() {

        var currentImage = "{{ getSingleMedia($user_data,'profile_image', null) }}";


        if (currentImage !== "") {

            var fileName = currentImage.split('/').pop();

            $('#imagelabel').text(fileName);
        }
    });


    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return true;
        }
        return false;
    }
})
// })(jQuery);
</script>