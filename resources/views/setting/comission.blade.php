<x-master-layout>
    {{ Form::open(['route' => ['provider.destroy', $providerdata->id], 'method' => 'delete','data--submit'=>'provider'.$providerdata->id]) }}
    <main class="main-area">
        <div class="main-content">
            <div class="container-fluid">
                @include('partials._provider')
                <div class="card mb-30">
                    <div class="card-body p-30">
                        <div class="col-lg-12">
                            <div class="card overview-detail mb-0">
                                <div class="card-body">
                                    <div class="row">
                                        
                                        <div class="form-group col-md-4">
                                            {{ Form::label('type',trans('messages.type').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                            <input type="text" class="form-control" placeholder="{{$providerdata->providertype['type']}}" readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {{ Form::label('commission',trans('messages.commission').' <span class="text-danger">*</span>',['class'=>'form-control-label'], false ) }}
                                            <input type="text" class="form-control" placeholder="{{$providerdata->providertype['commission']}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    {{ Form::close() }}
   
</x-master-layout>