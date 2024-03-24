{{ Form::open(['method' => 'POST','route' => ['envSetting'],'data-toggle'=>'validator']) }}

    {{ Form::hidden('id', null, ['class' => 'form-control'] ) }}
    {{ Form::hidden('page', $page, ['class' => 'form-control'] ) }}
    {{ Form::hidden('type', 'mail', ['class' => 'form-control'] ) }}

    
    <div class="col-md-12 mt-20">
        <div class="row">
            @foreach(config('constant.MAIL_SETTING') as $key => $value)
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-control-label text-capitalize">{{ strtolower(str_replace('_',' ',$key)) }}</label>
                        @if( auth()->user()->hasRole('admin'))
                            <input type="{{$key=='MAIL_PASSWORD'?'password':'text'}}" value="{{ $value }}" name="ENV[{{$key}}]" class="form-control" placeholder="{{ config('constant.MAIL_PLACEHOLDER.'.$key) }}">
                        @else
                            <input type="{{$key=='MAIL_PASSWORD'?'password':'text'}}" value="" name="ENV[{{$key}}]" class="form-control" placeholder="{{ config('constant.MAIL_PLACEHOLDER.'.$key) }}">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{ Form::submit(__('messages.save'), ['class'=>"btn btn-md btn-primary float-md-right"]) }}
    {{ Form::close() }}