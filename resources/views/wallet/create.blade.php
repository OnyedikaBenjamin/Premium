<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-block card-stretch">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3">
                    <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                                <a href="{{ route('wallet.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @if($auth_user->can('providertype list'))
                            @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                {{ Form::model($wallet,['method' => 'POST','route'=>'wallet.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'wallet'] ) }}
                    {{ Form::hidden('id') }}
                        <div class="row">
                            <div class="form-group col-md-4">
                                {{ Form::label('title',__('messages.title').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                {{ Form::text('title',old('title'),['placeholder' => __('messages.title'),'class' =>'form-control','required']) }}
                                <small class="help-block with-errors text-danger"></small>
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.user') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                <br />
                                {{ Form::select('user_id', [ optional($wallet->providers)->id => optional($wallet->providers)->display_name ], optional($wallet->providers)->id, [
                                        'class' => 'select2js form-group',
                                        'id' => 'user_id',
                                        'required',
                                        'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.provider') ]),
                                        'data-ajax--url' => route('ajax-list', ['type' => 'provider-user']),
                                ]) }}                             
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('amount',__('messages.amount').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::number('amount',null, [ 'min' => 0, 'step' => 'any' , 'placeholder' => __('messages.amount'),'class' =>'form-control', 'required' ]) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('status',__('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'class' =>'form-control select2js','required']) }}
                            </div>
                        </div>
                    {{ Form::submit( trans('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-master-layout>
