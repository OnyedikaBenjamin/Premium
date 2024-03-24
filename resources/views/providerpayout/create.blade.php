<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                        <a href="{{ route('earning') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                {{ Form::model($payoutdata,['method' => 'POST','route'=>'providerpayout.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'providerpayout'] ) }}
                    {{ Form::hidden('provider_id') }}
                        <div class="row">
                            <div class="form-group col-md-12" id="payment_method_id"> 
                                {{ Form::label('method',trans('messages.method').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('payment_method',['bank' => __('messages.bank') , 'cash' => __('messages.cash'), 'wallet' => __('messages.wallet') ],old('method'),[ 'id' => 'method' ,'class' =>'form-control select2js','required']) }}
                            </div>

                            <div class="form-group col-md-12" id="select_bank" >
                                    {{ Form::label('bank', __('messages.select_bank',[ 'select' => __('messages.select_bank') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    <a href="{{ route('bank.create', ['provider_id' => $payoutdata->provider_id]) }}" class="mr-1 btn-link btn-link-hover"><i class="fa fa-plus-circle"></i> {{ trans('messages.add_form_title',['form' => trans('messages.bank')  ]) }}</a>
                                    <br />
                                    {{ Form::select('bank',[], [
                                        'class' => 'select2js form-group col-md-12  bank','required',
                                        'data-placeholder' => __('messages.select_bank',[ 'select' => __('messages.') ])
                                    ]) }}
                             </div>

                              <div class="form-group col-md-12"  id='payment_gateway'>
                                  <label class="form-control-label">{{__('messages.payment_gateway',['gateway'=>__('messages.payment_gateway')])}}</label><br/>
                                  <div class="form-check-inline">
                                      <label class="form-check-label">
                                          <input type="radio" class="form-check-input is_test" value="razorpayx" name="payment_gateway" data-type="razorpayx" {{ old('payment_gateway') == 'razorpayx' || !old('payment_gateway') ? 'checked' : '' }}>{{__('messages.razorx')}}
                                      </label>
                                  </div>
                                  <div class="form-check-inline">
                                      <label class="form-check-label">
                                          <input type="radio" class="form-check-input is_test" value="stripe" name="payment_gateway" data-type="stripe" {{ old('payment_gateway') == 'stripe' ? 'checked' : '' }} >{{__('messages.stripe')}}
                                      </label>
                                  </div>
                              
                                  <small class="help-block with-errors text-danger"></small>
                              </div>
                            
                          <!-- 
                             <div class="form-group col-md-12" id="payment_gateway"> 
                                {{ Form::label('payment_gateway',trans('messages.payment_gateway').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('payment_gateway',['razorpayx' => __('messages.razorx') , 'stripe' => __('messages.stripe')],old('payment_gateway'),[ 'id' => 'payment_gateway' ,'class' =>'form-control select2js','required']) }}
                            </div> -->
                          
                            <div class="form-group col-md-12 ">
                                {{ Form::label('description',__('messages.description'), ['class' => 'form-control-label']) }}
                                {{ Form::textarea('description', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
                            </div>
                            <div class="form-group col-md-12">
                                {{ Form::label('amount',__('messages.amount'), ['class' => 'form-control-label']) }}
                                {{ Form::number('amount',old('amount'),['placeholder' => __('messages.amount'),'class' =>'form-control', 'required|between:0,99.99', 'step' => 'any', 'min' => 0, 'max' => $payoutdata->amount ?? 0]) }}
                            </div>
                        </div>
                    {{ Form::submit( trans('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
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
                $(document).ready(function(){
                    var payment_method =  "{{ isset($provider_payouts->payment_method) ? $provider_payouts->payment_method : 0 }}";
                       
                    var provider_id = $('input[name="provider_id"]').val();
       
                    bankdetails(provider_id , bank);

                    $(document).on('change' , '#method' , function (){
                        var payment_method = $(this).val();

                        if(payment_method=='bank'){

                          $("#select_bank").removeClass("d-none");
                          $("#payment_gateway").removeClass("d-none");
                            bankdetails(provider_id,bank);

                        }else{

                           $('#select_bank').addClass("d-none");

                           $('#payment_gateway').addClass("d-none");
                           
                        }   
                       
                    })
                   
                })
                function bankdetails(provider_id , bank ="" ){
                    var bank_route = "{{ route('ajax-list', [ 'type' => 'bank','provider_id' =>'']) }}"+provider_id;
                    bank_route = bank_route.replace('amp;','');

                    $.ajax({
                        url: bank_route,
                        success: function(result){
                          
                            $('#bank').select2({
                                width: '100%',
                                placeholder: "{{ trans('messages.bank_name',['select' => trans('messages.bank_name')]) }}",
                                data: result.results
                            });
                            if(bank != null){
                                $("#bank_details").val(bank).trigger('change');
                            }
                        }
                    });
                }
        
               
            })(jQuery);


            window.onload = function() {
    if (window.history && window.history.pushState) {
        window.history.pushState('', null, '');
        window.onpopstate = function() {
            window.history.pushState('', null, '');
        };
    }
};
        </script>
    @endsection
</x-master-layout>
