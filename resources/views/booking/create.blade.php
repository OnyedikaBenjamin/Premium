<x-master-layout>
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                                <a href="{{ route('booking.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @if($auth_user->can('booking list'))
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($bookingdata,['method' => 'POST','route'=>'booking.store', 'data-toggle'=>"validator" ,'id'=>'booking'] ) }}
                            {{ Form::hidden('id') }}
                            <div class="row">
                                                                
                                <div class="form-group col-md-4">
                                    {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.service') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    <br />
                                    {{ Form::select('service_id', [optional($bookingdata->service)->id => optional($bookingdata->service)->name], optional($bookingdata->service)->id, [
                                            'class' => 'select2js form-group service',
                                            'required',
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.service') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'service']),
                                        ])
                                    }}
                                </div>
                                @if (auth()->user()->hasRole(['admin']))
                                    <div class="form-group col-md-4">
                                        {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.customer') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                        <br />
                                        {{ Form::select('customer_id', [optional($bookingdata->customer)->id => optional($bookingdata->customer)->display_name], optional($bookingdata->customer)->id, [
                                                'class' => 'select2js form-group customer',
                                                'required',
                                                'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.customer') ]),
                                                'data-ajax--url' => route('ajax-list', ['type' => 'user']),
                                            ])
                                        }}
                                    </div>
                                @else
                                    <input type="hidden" name="customer_id" value="{{$bookingdata->customer_id}}">
                                @endif
                                <div class="form-group col-md-4">
                                    {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.coupon') ]),['class'=>'form-control-label']) }}
                                    <br />
                                    {{ Form::select('coupon_id', [optional($bookingdata->coupon)->id => optional($bookingdata->coupon)->name], optional($bookingdata->coupon)->id, [
                                            'class' => 'select2js form-group coupon',
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.coupon') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'coupon']),
                                        ])
                                    }}
                                </div>
                                
                                <div class="form-group col-md-4">
                                    {{ Form::label('date',__('messages.date').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('date',old('date'),['placeholder' => __('messages.date'),'class' =>'form-control min-datetimepicker','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    {{ Form::label('address',__('messages.address').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::textarea('address', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-12">
                                    {{ Form::label('description',__('messages.description'), ['class' => 'form-control-label']) }}
                                    {{ Form::textarea('description', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
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