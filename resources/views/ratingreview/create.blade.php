<x-master-layout>
    <div class="container-fluid">
        <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('providerdocument list'))
                                <a href="{{ route('providerdocument.index') }}" class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($rating_review,['method' => 'POST','route'=>'ratingreview.store', 'enctype'=>'multipart/form-data', 'data-toggle'=>"validator" ,'id'=>'rating_review'] ) }}
                            {{ Form::hidden('id') }}
                            <div class="row">
                                <div class="form-group col-md-4">
                                        {{ Form::label('name', __('messages.select_name',[ 'select' => __('messages.customer') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                        <br />
                                        {{ Form::select('customer_id', [optional($rating_review->customer)->id => optional($rating_review->customer)->display_name], optional($rating_review->customer)->id, [
                                                'class' => 'select2js form-group customer',
                                                'required',
                                                'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.customer') ]),
                                                'data-ajax--url' => route('ajax-list', ['type' => 'user']),
                                            ])
                                        }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('service_id', __('messages.select_name',[ 'select' => __('messages.service') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    <br />
                                    {{ Form::select('service_id', [optional($rating_review->service)->id => optional($rating_review->service)->name], optional($rating_review->service)->id, [
                                            'class' => 'select2js form-group service',
                                            'required',
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.service') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'service']),
                                        ])
                                    }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('rating',trans('messages.rating').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                                    {{ Form::select('rating',['1' => 1 , '2' => 2,'3' => 3 , '4' => 4, '5' => 5],old('rating'),[ 'id' => 'rating' ,'class' =>'form-control select2js','required']) }}
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('review',__('messages.review').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                    {{ Form::textarea('review', null, ['class'=>"form-control textarea" , 'rows'=>3  , 'placeholder'=> __('messages.description') ]) }}
                                    <small class="help-block with-errors text-danger"></small>
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