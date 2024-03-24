<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs nav-fill tabslink payment-view-tabs" id="tab-text" role="tablist">
                <li class="nav-item payment-link">
                    <a href="javascript:void(0)" data-href="{{ route('booking_layout_page',$bookingdata->id) }}?tabpage=info" data-target=".payment_paste_here" data-toggle="tabajax" class="nav-link  {{$tabpage=='info'?'active':''}}" rel="tooltip"> {{ __('messages.info') }}</a>
                </li>
                <li class="nav-item payment-link">
                    <a href="javascript:void(0)" data-href="{{ route('booking_layout_page',$bookingdata->id) }}?tabpage=status" data-target=".payment_paste_here" data-toggle="tabajax" class="nav-link  {{$tabpage=='status'?'active':''}}" rel="tooltip"> {{__('messages.status')}}</a>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent-1">
                        <div class="tab-pane active p-1">
                            <div class="payment_paste_here"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @section('bottom_script')
    <script>
        $(document).ready(function(event) {
            var $this = $('.payment-link').find('a.active');
            loadurl = "{{route('booking_layout_page',$bookingdata->id)}}?tabpage={{$tabpage}}";
            targ = $this.attr('data-target');
            
            id = this.id || '';

            $.post(loadurl, {
                '_token': $('meta[name=csrf-token]').attr('content')
            }, function(data) {
                $(targ).html(data);
            });

            $this.tab('show');
        });
         $('.payment_paste_here').on('change','.booking-Status',function(){
            $.post("{{ route('bookingStatus.update') }}", {
                '_token': $('meta[name=csrf-token]').attr('content'), 
                bookingId:"{{ request()->booking }}",
                status: $(this).val(),
                type: $(this).attr("type"),
            }, function(data) {
             window.location.reload();
            });
        });
        

    </script>
    @endsection
</x-master-layout>