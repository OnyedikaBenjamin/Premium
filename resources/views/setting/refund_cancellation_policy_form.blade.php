<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? __('messages.list') }}</h5>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($setting_data,['method' => 'POST','route'=>'refund-cancellation-policy-save', 'data-toggle'=>"validator" ] ) }}
                        {{ Form::hidden('id') }}
                        <div class="row">
                            <div class="form-group col-md-12">
                                {{ Form::label('refund_cancellation_policy',__('messages.refund_cancellation_policy'), ['class' => 'form-control-label']) }}
                                {{ Form::textarea('value', null, ['class'=> 'form-control tinymce-refund_cancellation_policy' , 'placeholder'=> __('messages.refund_cancellation_policy') ]) }}
                            </div>
                        </div>
                        {{ Form::submit( __('messages.save'), ['class'=>'btn btn-md btn-primary float-right']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@section('bottom_script')
    <script>
        (function($) {
            $(document).ready(function(){
                tinymceEditor('.tinymce-refund_cancellation_policy',' ',function (ed) {

                }, 450)
            
            });

        })(jQuery);
    </script>
@endsection
</x-master-layout>