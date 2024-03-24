{{ Form::model($setting_value, ['method' => 'POST','route' => ['configUpdate'],'enctype'=>'multipart/form-data','data-toggle'=>'validator']) }}

    {{ Form::hidden('id', null, ['class' => 'form-control'] ) }}
    {{ Form::hidden('page', $page, ['class' => 'form-control'] ) }}
    

    <div class="row">
        @foreach($setting as $key => $value)
            <div class="col-md-12 col-sm-12 card config-info-card shadow mb-10">
                <div class="card-header">
                    <h4>{{ $key }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($value as $sub_keys => $sub_value)
                            @php
                                $data=null;
                                foreach($setting_value as $v){

                                    if($v->key==($key.'_'.$sub_keys)){
                                        $data = $v;
                                    }
                                }
                                $class = 'col-md-4';
                                $type = 'text';
                                switch ($key){
                                    case 'COLOR' : 
                                        $type = 'color';
                                        break;
                                    case 'DISTANCE' :
                                        $type = 'number';
                                        break;
                                    default : break;
                                }
                                $distance_label = '';

                                $distance = '';
                                $distance_type = '';
                                if(isset($data) && $data->value != null && $sub_keys == 'TYPE' )
                                {
                                    $distance_type = $data->value;
                                }
                                if($key == 'DISTANCE' && $sub_keys == 'RADIOUS')
                                {
                                    $distance = 50;
                                }

                            @endphp
                            <div class=" {{ $class }} col-sm-12">
                                <div class="form-group">
                                    <label for="{{ $key.'_'.$sub_keys }}">{{ str_replace('_',' ',$sub_keys) }} </label>
                                    {{ Form::hidden('type[]', $key , ['class' => 'form-control'] ) }}
                                    <input type="hidden" name="key[]" value="{{ $key.'_'.$sub_keys }}">
                                    @if($key == 'CURRENCY' && $sub_keys == 'COUNTRY_ID')
                                        {{ Form::select('value[]', isset($data->country) ? [optional($data->country)->id => optional($data->country)->name ." ( ". optional($data->country)->symbol ." ) " ] : [], isset($data->country) ? optional($data->country)->id : null , [
                                            'class' => 'select2js form-group country',
                                            'id' => $key.'_'.$sub_keys,
                                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.country') ]),
                                            'data-ajax--url' => route('ajax-list', ['type' => 'currency']),
                                            ]) 
                                        }}
                                    @elseif($key == 'CURRENCY' && $sub_keys == 'POSITION')
                                    {{ Form::select('value[]',['left' => __('messages.left') , 'right' => __('messages.right') ], isset($data) ? $data->value : 'left',[ 'class' =>'form-control select2js']) }}
                                    @elseif($key == 'DISTANCE' && $sub_keys == 'TYPE')
                                    {{ Form::select('value[]',['km' => __('messages.km') , 'mile' => __('messages.mile') ], $distance_type,[ 'class' =>'form-control select2js']) }}
                                    @else
                                        <input type="{{ $type }}" name="value[]" value="{{ isset($data) ? $data->value : $distance }}" id="{{ $key.'_'.$sub_keys }}" {{ $type == 'number' ? "min=0 step='any'" : '' }} class="form-control" placeholder="{{ str_replace('_',' ',$sub_keys) }}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-12">
                            {!! Form::submit('Save',['class'=>'btn btn-md btn-primary']) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endForeach
    </div>
{{ Form::submit(__('messages.save'), ['class'=>"btn btn-md btn-primary float-md-right"]) }}
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $('.select2js').select2();
    });
</script>
