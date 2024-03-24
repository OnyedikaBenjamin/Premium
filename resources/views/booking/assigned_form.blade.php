<!-- Modal -->

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $pageTitle }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
       {{ Form::open(['route' => 'booking.assigned','method' => 'post','data-toggle'=>"validator"]) }}
        <div class="modal-body">

           {{ Form::hidden('id',$bookingdata->id) }}
            <div class="row">
                
                <div class="col-md-12 form-group ">
                    {{ Form::label('handyman_id', __('messages.select_name',[ 'select' => __('messages.handyman') ]).' <span class="text-danger">*</span>',['class'=>'form-control-label'],false) }}
                    <br />
                    @php
                        if($bookingdata->booking_address_id != null)
                        {
                            $route = route('ajax-list', ['type' => 'handyman', 'provider_id' => $bookingdata->provider_id, 'booking_id' => $bookingdata->id ]);
                        } else {
                            $route = route('ajax-list', ['type' => 'handyman', 'provider_id' => $bookingdata->provider_id ]);
                        }
                        $assigned_handyman = $bookingdata->handymanAdded->mapWithKeys(function ($item) {
                            return [$item->handyman_id => optional($item->handyman)->display_name];
                        });
                    @endphp
                    {{ Form::select('handyman_id[]', $assigned_handyman, $bookingdata->handymanAdded->pluck('handyman_id'), [
                            'class' => 'select2js handyman',
                            'id' => 'handyman_id',
                            'required',
                            'data-placeholder' => __('messages.select_name',[ 'select' => __('messages.handyman') ]),
                            'data-ajax--url' => $route,
                        ]) }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">{{ trans('messages.close') }}</button>
            <button type="submit" class="btn btn-md btn-primary" id="btn_submit" data-form="ajax" >{{ trans('messages.save') }}</button>
        </div>
        {{ Form::close() }}
    </div>
</div>
<script>
    $('#handyman_id').select2({
        width: '100%',
        placeholder: "{{ __('messages.select_name',['select' => __('messages.handyman')]) }}",
    });
</script>