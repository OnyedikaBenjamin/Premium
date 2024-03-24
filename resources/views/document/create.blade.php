<x-master-layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('document list'))
                                <a href="{{ route('document.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($documentdata,['method' => 'POST','route'=>'document.store', 'data-toggle'=>"validator" ,'id'=>'documents'] ) }}
                            {{ Form::hidden('id') }}
                            <div class="row">
                                <div class="form-group col-md-12">
                                    {{ Form::label('name',trans('messages.name').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::text('name',old('name'),['placeholder' => trans('messages.name'),'class' =>'form-control','required']) }}
                                    <small class="help-block with-errors text-danger"></small>
                                </div>

                                <div class="form-group col-md-12">
                                    {{ Form::label('status',trans('messages.status').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::select('status',['1' => __('messages.active') , '0' => __('messages.inactive') ],old('status'),[ 'id' => 'status' ,'class' =>'form-control select2js','required']) }}
                                </div>

                                <div class="form-group col-md-12">
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        {{ Form::checkbox('is_required', $documentdata->is_required, null, ['class' => 'custom-control-input' , 'id' => 'is_required' ]) }}
                                        <label class="custom-control-label" for="is_required">{{ __('messages.is_required')  }}
                                        </label>
                                    </div>
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