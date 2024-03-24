{{ Form::model($settings, ['method' => 'POST','route' => ['saveEarningTypeSetting'],'enctype'=>'multipart/form-data','data-toggle'=>'validator']) }}

{{ Form::hidden('id', null, array('placeholder' => 'id','class' => 'form-control')) }}
{{ Form::hidden('page', $page, array('placeholder' => 'id','class' => 'form-control')) }}
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            {{ Form::label('earning_type',__('messages.earning_type_provider').' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
            {{ Form::select('earning_type',['commission' => __('messages.commission') , 'subscription' => __('messages.subscription') ],old('earning_type'),[ 'class' =>'form-control select2js','required']) }}
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
