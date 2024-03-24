{{ Form::model($settings, ['method' => 'POST','route' => ['settingsUpdates'],'enctype'=>'multipart/form-data','data-toggle'=>'validator']) }}

{{ Form::hidden('id', null, array('placeholder' => 'id','class' => 'form-control')) }}
{{ Form::hidden('page', $page, array('placeholder' => 'id','class' => 'form-control')) }}
<div class="row">
    <div class="col-lg-6"> 
        <div class="form-group">
            <label for="avatar" class="col-sm-3 form-control-label">{{ __('messages.logo') }}</label>
            <div class="col-sm-12">

                <div class="row">
                    <div class="col-sm-4">
                        <img src="{{ getSingleMedia($settings,'site_logo') }}" width="100"  id="site_logo_preview" alt="site_logo" class="image site_logo site_logo_preview">
                        @if(getMediaFileExit($settings, 'site_logo'))
                            <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $settings->id, 'type' => 'site_logo']) }}"
                                data--submit="confirm_form"
                                data--confirmation='true'
                                data--ajax="true"
                                title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                data-message='{{ __("messages.remove_file_msg") }}'>
                                <i class="ri-close-circle-line"></i>
                            </a>
                        @endif
                    </div>
                    <div class="col-sm-8 mt-sm-0 mt-2">
                        <div class="custom-file col-md-12">
                            {{ Form::file('site_logo', ['class'=>"custom-file-input custom-file-input-sm detail" , 'id'=>"site_logo" , 'lang'=>"en" , 'accept'=>"image/*"]) }}
                            <label class="custom-file-label upload-label" for="site_logo">{{ __('messages.logo') }}</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-group">
            <label for="avatar" class="col-sm-6 form-control-label">{{ __('messages.favicon') }}</label>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="{{ getSingleMedia($settings,'site_favicon') }}" height="30"  id="site_favicon_preview" alt="site_favicon" class="image site_favicon site_favicon_preview">
                        @if(getMediaFileExit($settings, 'site_favicon'))
                            <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $settings->id, 'type' => 'site_favicon']) }}"
                                data--submit="confirm_form"
                                data--confirmation='true'
                                data--ajax="true"
                                title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}'
                                data-message='{{ __("messages.remove_file_msg") }}'>
                                <i class="ri-close-circle-line"></i>
                            </a>
                        @endif
                    </div>
                    <div class="col-sm-8 mt-sm-0 mt-2">
                        <div class="custom-file col-md-12">
                            {{ Form::file('site_favicon', ['class'=>"custom-file-input custom-file-input-sm detail" , 'id'=>"site_favicon" , 'lang'=>"en" , 'accept'=>"image/*"]) }}
                            <label class="custom-file-label upload-label" for="site_favicon">{{ __('messages.favicon') }}</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.site_name') }}</label>
            <div class="col-sm-12">
                {{ Form::text('site_name', null, ['class'=>"form-control" ,'placeholder'=> __('messages.site_name') ]) }}
            </div>
        </div>
        
        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.site_description') }}</label>
            <div class="col-sm-12">
                {{ Form::textarea('site_description', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.site_description')]) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.inquriy_email') }}</label>
            <div class="col-sm-12">
                {{ Form::email('inquriy_email', null, ['class'=>"form-control" ,'placeholder'=> __('messages.inquriy_email') ]) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.helpline_number') }}</label>
            <div class="col-sm-12">
                {{ Form::text('helpline_number', null, ['class'=>"form-control helpline_number" ,'placeholder'=> __('messages.helpline_number') ]) }}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="time_zone" class="col-sm-6 form-control-label">{{ __('messages.timezone') }}</label>
            <div class="col-sm-12">
                <select class="form-control select2js" name="time_zone" id="time_zone"
                        data-ajax--url="{{ route('ajax-list', ['type' => 'time_zone']) }}"
                        data-ajax--cache="true">
                    @if(isset($settings->time_zone))
                        <option value="{{ $settings->time_zone }}" selected="">{{ timeZoneList()[$settings->time_zone] }}</option>
                    @endif

                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="default_language" class="col-sm-12 form-control-label">{{ __('messages.default_language') }}</label>
            <div class="col-sm-12">
                <select class="form-control select2js default_language" name="ENV[DEFAULT_LANGUAGE]" id="default_language">
                    @foreach(languagesArray() as $language)
                        <option value="{{ $language['id'] }}" {{ config('app.locale') == $language['id']  ? 'selected' : '' }}  >{{ $language['title'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group d-none">
            <label for="language_option" class="col-sm-12 form-control-label">{{ __('messages.language_option') }}</label>
            <div class="col-sm-12">
                <select class="form-control select2js language_option" name="language_option[]" id="language_option" multiple>
                    @foreach(languagesArray() as $language)
                        @if(config('app.locale') == $language['id']  )
                            <option value="{{ $language['id'] }}"  disabled="">{{ $language['title'] }}</option>
                        @else
                            <option value="{{ $language['id'] }}" {{in_array($language['id'],$settings->language_option) ? 'selected' : '' }}  >{{ $language['title'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.facebook_url') }}</label>
            <div class="col-sm-12">
                {{ Form::text('facebook_url', null, ['class'=>"form-control" , 'placeholder'=>__('messages.facebook_url_placeholder') ]) }}
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.twitter_url') }}</label>
            <div class="col-sm-12">
                {{ Form::text('twitter_url', null, ['class'=>"form-control" , 'placeholder'=>__('messages.twitter_url_placeholder') ]) }}
            </div>
        </div>
        
        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.linkedin_url') }}</label>
            <div class="col-sm-12">
                {{ Form::text('linkedin_url', null, ['class'=>"form-control" , 'placeholder'=> __('messages.linkedin_url_placeholder') ]) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.instagram_url') }}</label>
            <div class="col-sm-12">
                {{ Form::text('instagram_url', null, ['class'=>"form-control" , 'placeholder'=> __('messages.instagram_url_placeholder') ]) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.youtube_url') }}</label>
            <div class="col-sm-12">
                {{ Form::text('youtube_url', null, ['class'=>"form-control" , 'placeholder'=> __('messages.youtube_url_placeholder') ]) }}
            </div>
        </div>

        <!-- <hr> -->
        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.copyright_text') }}</label>
            <div class="col-sm-12">
                {{ Form::text('site_copyright', null, ['class'=>"form-control" , 'placeholder'=>__('messages.copyright_text')]) }}
            </div>
        </div>
        
    </div>
     <div class="col-lg-12"> 
        <div class="form-group">
            <div class="col-md-offset-3 col-sm-12 ">
                {{ Form::submit(__('messages.save'), ['class'=>"btn btn-md btn-primary float-md-right"]) }}
            </div>
        </div>
     </div>
</div>
{{ Form::close() }}
<script>
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
                case 'ico':
                    return true;
            }
            return false;
        }
    function readURL(input,className) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var res = isImage(input.files[0].name);
            if(res == false){
                var msg = 'Image should be png/PNG, jpg/JPG & jpeg/JPG.';
                Snackbar.show({text: msg ,pos: 'bottom-right',backgroundColor:'#d32f2f',actionTextColor:'#fff'});
                $(input).val("");
                return false;
            }
            reader.onload = function(e){
                $(document).find('img.'+className).attr('src', e.target.result);
                $(document).find("label."+className).text((input.files[0].name));
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function (){
        $('.select2js').select2();
        $(document).on('change','#site_logo',function(){
            readURL(this,'site_logo');
        });
        $(document).on('change','#site_favicon',function(){
            readURL(this,'site_favicon');
        });

        $('.default_language').on('change', function (e) {
            var id= $(this).val();
            $('.language_option option:disabled').prop('selected',true);
            $('.language_option option').prop('disabled',false);

            $('.language_option option').each(function(index, val){
                var $this = $(this);
                if(id == $this.val()){
                $this.prop('disabled',true);
                $this.prop('selected',false);
                }
            });
            $('.language_option').select2("destroy").select2();
        });
    })

    $(document).on('keyup', '.helpline_number', function() {
        var contactNumberInput = document.getElementById('helpline_number');
        var inputValue = contactNumberInput.value;
        inputValue = inputValue.replace(/[^0-9+\- ]/g, '');
        if (inputValue.length > 15) {
            inputValue = inputValue.substring(0, 15);
        } 
        contactNumberInput.value = inputValue;
        if (inputValue.match(/^[0-9+\- ]+$/)) {
            $('.helpline_number').text('');
        } else {
            $('.helpline_number').text('Please enter a valid mobile number');
        }
    });
</script>
