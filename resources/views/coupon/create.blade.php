<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                            @if($auth_user->can('coupon list'))
                            <a href="{{ route('coupon.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($coupondata,['method' => 'POST','route'=>'coupon.store', 'data-toggle'=>"validator" ,'id'=>'coupon'] ) }}
                        {{ Form::hidden('id') }}
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('code',__('messages.code').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                @if( $coupondata->id == null )
                                {{ Form::text('code',old('code'),['placeholder' => __('messages.code'),'class' =>'form-control','required']) }}
                                @else
                                <p>{{ $coupondata->code }}</p>
                                @endif
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('discount_type',__('messages.discount_type').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('discount_type',['fixed' => __('messages.fixed') , 'percentage' => __('messages.percentage') ],old('status'),[ 'class' =>'form-control select2js','required']) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('discount',__('messages.discount').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::number('discount',null, [ 'min' => 0, 'step' => 'any' , 'placeholder' => __('messages.discount'),'class' =>'form-control','required']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('expire_date',__('messages.expire_date').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('expire_date',old('expire_date'),['placeholder' => __('messages.expire_date'),'class' =>'form-control datetimepicker','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.service') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                @php
                                $assigned_service = $coupondata->serviceAdded->mapWithKeys(function ($item) {
                                return [$item->service_id => optional($item->service)->name];
                                });
                                @endphp
                                {{ Form::select('service_id[]', $assigned_service, $coupondata->serviceAdded->pluck('service_id'), [
                                            'class' => 'select2js form-group service',
                                            'required',
                                            'multiple' => 'multiple',
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.service') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'service-list']),
                                        ]) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('status',__('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'id' => 'role' ,'class' =>'form-control select2js','required']) }}
                            </div>

                        </div>

                        {{ Form::submit( __('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>