{{ Form::model($payment_data, ['method' => 'POST','route' => ['paymentsettingsUpdates'],'enctype'=>'multipart/form-data','data-toggle'=>'validator']) }}

{{ Form::hidden('id', null, array('placeholder' => 'id','class' => 'form-control')) }}
{{ Form::hidden('type', $tabpage, array('placeholder' => 'id','class' => 'form-control')) }}
<div class="row">
    <div class="form-group col-md-12">
        <label for="enable_razorpay">{{__('messages.payment_on',['gateway'=>__('messages.razor')])}}</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" name="status" id="enable_razorpay" {{!empty($payment_data) && $payment_data->status == 1 ? 'checked' : ''}}>
            <label class="custom-control-label" for="enable_razorpay"></label>
        </div>
    </div>
</div>
<div class="row"  id='enable_razorpay_payment'>
    <div class="form-group col-md-12">
        <label class="form-control-label">{{__('messages.payment_option',['gateway'=>__('messages.razorpay')])}}</label><br/>
        <div class="form-check-inline">
            <label class="form-check-label">
                <input type="radio" class="form-check-input is_test" value="on" name="is_test" data-type="is_test_mode"  {{!empty($payment_data) &&  $payment_data->is_test == 1 ? 'checked' :''}}>{{__('messages.is_test_mode')}}
            </label>
        </div>
        <div class="form-check-inline">
            <label class="form-check-label">
                <input type="radio" class="form-check-input is_test" value="off" name="is_test" data-type="is_live_mode" {{!empty($payment_data) &&  $payment_data->is_test == 0 ? 'checked' :''}}>{{__('messages.is_live_mode')}}
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
        {{ Form::label('razorx_url',trans('messages.razorx_url').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('razorx_url',old('razorx_url'),['id'=>'razorx_url','placeholder' => trans('messages.razorx_url'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('razorx_account',trans('messages.razorx_account').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('razorx_account',old('razorx_account'),['id'=>'razorx_account','placeholder' => trans('messages.razorx_account'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('razorx_key',trans('messages.razorx_key').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('razorx_key',old('razorx_key'),['id'=>'razorx_key','placeholder' => trans('messages.razorx_key'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('razorx_secret',trans('messages.razorx_secret').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
        {{ Form::text('razorx_secret',old('razorx_secret'),['id'=>'razorx_secret','placeholder' => trans('messages.razorx_secret'),'class' =>'form-control','required']) }}
        <small class="help-block with-errors text-danger"></small>
    </div>
</div>
{{ Form::submit(__('messages.save'), ['class'=>"btn btn-md btn-primary float-md-right"]) }}
{{ Form::close() }}
<script>
var enable_razorpay = $("input[name='status']").prop('checked');
checkPaymentTabOption(enable_razorpay);


$('#enable_razorpay').change(function(){
    value = $(this).prop('checked') == true ? true : false;
    checkPaymentTabOption(value);
});
function checkPaymentTabOption(value){
    if(value == true){
        $('#enable_razorpay_payment').removeClass('d-none');
    }else{
      
        $('#enable_razorpay_payment').addClass('d-none');
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
            var razorx_url=razorx_key=razorx_secret=razorx_account=title = '';

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
                    var razorx_url = obj.razorx_url;
                    var razorx_account = obj.razorx_account;
                    var razorx_key = obj.razorx_key;
                    var razorx_secret = obj.razorx_secret;
                }

                $('#razorx_key').val(razorx_key)
                $('#razorx_account').val(razorx_account)
                $('#razorx_key').val(razorx_key)
                $('#razorx_secret').val(razorx_secret)
                $('#title').val(title)
            
            }
        },
        error: function(error) {
         console.log(error);
        }
    });
}
</script>