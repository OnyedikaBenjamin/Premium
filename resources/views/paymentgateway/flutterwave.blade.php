{{ Form::model($payment_data, ['method' => 'POST','route' => ['paymentsettingsUpdates'],'enctype'=>'multipart/form-data','data-toggle'=>'validator']) }}

{{ Form::hidden('id', null, array('placeholder' => 'id','class' => 'form-control')) }}
{{ Form::hidden('type', $tabpage, array('placeholder' => 'id','class' => 'form-control')) }}
<div class="row">
    <div class="form-group col-md-12">
        <label for="enable_flutterwave">{{__('messages.payment_on',['gateway'=>__('messages.flutterwave')])}}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="status" id="enable_flutterwave" {{!empty($payment_data) && $payment_data->status == 1 ? 'checked' : ''}}>
            <label class="custom-control-label" for="enable_flutterwave"></label>
        </div>
    </div>
</div>
<div class="row" id='enable_flutterwave_payment'>
    <div class="form-group col-md-12">
        <label class="form-control-label">{{__('messages.payment_option',['gateway'=>__('messages.flutterwave')])}}</label><br/>
        <div class="form-check-inline">
            <label class="form-check-label">
                <input type="radio" class="form-check-input is_test" value="on" name="is_test" data-type='is_test_mode' {{!empty($payment_data) && $payment_data->is_test == 1 ? 'checked' :''}}>{{__('messages.is_test_mode')}}
            </label>
        </div>
        <div class="form-check-inline">
            <label class="form-check-label">
                <input type="radio" class="form-check-input is_test" value="off" name="is_test" data-type='is_live_mode' {{!empty($payment_data) && $payment_data->is_test == 0 ? 'checked' :''}}>{{__('messages.is_live_mode')}}
            </label>
        </div>
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('title',trans('messages.gateway_name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('title',old('title'),['id'=>'title','placeholder' => trans('messages.title'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('flutterwave_public',trans('messages.flutterwave_public').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('flutterwave_public',old('flutterwave_public'),['id'=>'flutterwave_public','placeholder' => trans('messages.flutterwave_public'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('flutterwave_secret',trans('messages.flutterwave_secret').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('flutterwave_secret',old('flutterwave_secret'),['id'=>'flutterwave_secret','placeholder' => trans('messages.flutterwave_secret'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('flutterwave_encryption',trans('messages.flutterwave_encryption').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('flutterwave_encryption',old('flutterwave_encryption'),['id'=>'flutterwave_encryption','placeholder' => trans('messages.flutterwave_encryption'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
</div>
{{ Form::submit(__('messages.save'), ['class'=>"btn btn-md btn-primary float-md-right"]) }}
{{ Form::close() }}
<script>
var enable_flutterwave = $("input[name='status']").prop('checked');
checkPaymentTabOption(enable_flutterwave);

$('#enable_flutterwave').change(function(){
    value = $(this).prop('checked') == true ? true : false;
    checkPaymentTabOption(value);
});
function checkPaymentTabOption(value){
    if(value == true){
        $('#enable_flutterwave_payment').removeClass('d-none');
    }else{
        $('#enable_flutterwave_payment').addClass('d-none');
    }
}
var get_value = $('input[name="is_test"]:checked').data("type");
getConfig(get_value)
$('.is_test').change(function(){
    value = $(this).prop('checked') == true ? true : false;
    type = $(this).data("type");
    getConfig(type)

});

function getConfig(type){
    var _token   = $('meta[name="csrf-token"]').attr('content');
    var page =  "{{$tabpage}}";
    $.ajax({
        url: "/get_payment_config",
        type:"POST",
        data:{
          type:type,
          page:page,
          _token: _token
        },
        success:function(response){
            var obj = '';
            var flutterwave_public=flutterwave_secret=flutterwave_encryption=title = '';

            if(response){
            
                if(response.data.type == 'is_test_mode'){
                    obj = JSON.parse(response.data.value);
                }else{
                    obj = JSON.parse(response.data.live_value);
                }

                if(response.data.title != ''){
                    title = response.data.title
                }
                
                if(obj !== null){
                    var flutterwave_public = obj.flutterwave_public;
                    var flutterwave_secret = obj.flutterwave_secret;
                    var flutterwave_encryption = obj.flutterwave_encryption;
                }

                $('#flutterwave_public').val(flutterwave_public)
                $('#flutterwave_secret').val(flutterwave_secret)
                $('#flutterwave_encryption').val(flutterwave_encryption)
                $('#title').val(title)
            
            }
        },
        error: function(error) {
         console.log(error);
        }
    });
}
</script>
