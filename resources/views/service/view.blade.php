<x-master-layout>
    @include('partials._provider')
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs nav-fill tabslink" id="tab-text" role="tablist">
                <li class="nav-item payment-link">
                    <a href="javascript:void(0)" data-href="{{ route('provider_detail_pages') }}?tabpage=all-plan&providerid={{request()->service}}" data-target=".payment_paste_here" data-toggle="tabajax" class="nav-link  {{$tabpage=='all-plan'?'active':''}}" rel="tooltip"> {{ __('messages.all') }}</a>
                </li>
                <li class="nav-item payment-link">
                    <a href="javascript:void(0)" data-href="{{ route('provider_detail_pages') }}?tabpage=subscribe-plan&providerid={{request()->service}}" data-target=".payment_paste_here" data-toggle="tabajax" class="nav-link  {{$tabpage=='subscribe-plan'?'active':''}}" rel="tooltip"> {{__('messages.service_subscribe')}}</a>
                    
                </li>
                <li class="nav-item payment-link">
                    <a href="javascript:void(0)" data-href="{{ route('provider_detail_pages') }}?tabpage=unsubscribe-plan&providerid={{request()->service}}" data-target=".payment_paste_here" data-toggle="tabajax" class="nav-link  {{$tabpage=='unsubscribe-plan'?'active':''}}" rel="tooltip"> {{__('messages.service_unsubscribe')}}</a>
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
    @section('bottom_script')
    <script>
        $(document).ready(function(event) {
            var $this = $('.payment-link').find('a.active');
            loadurl = '{{route('provider_detail_pages')}}?tabpage={{$tabpage}}&providerid={{request()->service}}';

            targ = $this.attr('data-target');

            id = this.id || '';

            $.get(loadurl, {
                '_token': $('meta[name=csrf-token]').attr('content'),
                'providerId': "{{ $providerdata->id }}"
            }, function(data) {
                $(targ).html(data);
            });

            $this.tab('show');
            return false;
        });
    </script>
    @endsection
</x-master-layout>