<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                                <a href="{{ route('plans.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($plan,['method' => 'POST','route'=>'plans.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'plan'] ) }}
                            {{ Form::hidden('id') }}                            
                            <div class="row">
                                <div class="form-group col-md-4">
                                    {{ Form::label('title',trans('messages.title').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('title',old('title'),['placeholder' => trans('messages.title'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('type',trans('messages.type').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::select('type',['monthly' => __('messages.monthly') , 'yearly' => __('messages.yearly') ],old('type'),[ 'id' => 'type' ,'class' =>'form-control select2js','required']) }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('duration',trans('messages.duration').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::select('duration',['1' => '1' , '2' => '2','3' => '3','4' => '4','5' => '5','6' => '6','7' => '7','8' => '8','9' => '9','10' => '10','11' => '11','12' => '12' ],old('duration'),[ 'id' => 'duration' ,'class' =>'form-control select2js','required']) }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('amount',__('messages.amount').' <span class="text-danger">*</span>', ['class' => 'form-control-label'],false) }}
                                    {{ Form::number('amount',old('amount'),['placeholder' => __('messages.amount'),'class' =>'form-control', 'required', 'step' => 'any', 'min' => 0]) }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('status',trans('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'id' => 'role' ,'class' =>'form-control select2js','required']) }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('plan_type',trans('messages.plan_limitation').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    <select class='form-control select2js' id='plan_limitation' name="plan_type">
                                        @foreach($plan_type as $value)
                                            <option value="{{$value->value}}" data-type="{{$value->value}}" {{ $plan->plan_type == $value['value']  ? 'selected' : '' }} >{{$value->label}}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 ">
                                    {{ Form::label('description',__('messages.description'), ['class' => 'form-control-label']) }}
                                    {{ Form::textarea('description', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
                                </div>
                            </div>
                            <div>
                                @foreach($plan_limit as $key => $value)
                                <?php
                                    $planValue = $plan->planlimit;
                                    if(!empty($planValue)){
                                        $planValue = $plan->planlimit->plan_limitation;
                                        if(!array_key_exists('is_checked',$planValue[$value->value])){
                                            $planValue[$value->value]['is_checked'] = 'off';
                                        }
                                    }else{
                                        $planValue = null;
                                    }
                                   
                                ?>
                            
                                    <div class="row d-none show-checklist" id="show-checklist">
                                        <div class="form-group col-md-6" >
                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                {{ Form::checkbox("plan_limitation[$value->value][is_checked]", $planValue!= null ? $planValue[$value->value]['is_checked'] : null, $planValue!= null && $planValue[$value->value]['is_checked'] == 'on' ? true : false, ['class' => 'custom-control-input checklist' , 'id' => "enable_".$value->value ,'onClick' => "showCheckLimitData('enable_$value->value')"  ]) }}
                                                <label class="custom-control-label" for="{{'enable_'.$value->value}}">{{__('messages.plan_limitations',['keyword' => __('messages.'.$value->value)] )  }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class=" col-md-6 d-none {{'enable_'.$value->value}}" id="show-limit-{{$key}}">
                                            <div class="form-group">
                                                {{ Form::label('service_limit',__('messages.limit'), ['class' => 'form-control-label']) }}
                                                {{ Form::number("plan_limitation[$value->value][limit]",$planValue!= null ? $planValue[$value->value]['limit'] : null,['placeholder' => __('messages.plan_limitations',['keyword' => __('messages.'.$value->value)] ),'class' =>'form-control', 'step' => 'any', 'min' => 0]) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row d-none show_trial_period">
                                <div class="form-group col-md-4">
                                    {{ Form::label('trial_period',__('messages.trial_period'), ['class' => 'form-control-label']) }}
                                    {{ Form::number('trial_period',old('trial_period'),['placeholder' => __('messages.trial_period'),'class' =>'form-control', 'step' => 'any', 'min' => 0]) }}
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
                $(document).ready(function(){
                    $(".checklist:checkbox").each(function() {
                        if ($(this).is(":checked")) {
                            showCheckLimitData($(this).attr("id"));
                        }
                    });
                    var value = $("#plan_limitation option:selected").attr('data-type');
                    showLimitCheckbox(value)

                    $(document).on('change' , '#plan_limitation' , function (){
                        var data = $("#plan_limitation option:selected").attr('data-type');
                        showLimitCheckbox(data)
                    })

                    function showLimitCheckbox(type){
                        if(type === 'limited'){
                            $('.show-checklist').removeClass('d-none')
                        }else{
                            $('.show-checklist').addClass('d-none')
                        }
                        if(type === 'free'){
                            $('.show_trial_period').removeClass('d-none')
                        }else{
                            $('.show_trial_period').addClass('d-none')
                        }
                    }
                });
            })(jQuery);
        </script>
    @endsection
</x-master-layout>