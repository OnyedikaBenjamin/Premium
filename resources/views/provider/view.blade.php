<x-master-layout>
    {{ Form::open(['route' => ['provider.destroy', $providerdata->id], 'method' => 'delete', 'data--submit' => 'provider' . $providerdata->id]) }}
    <main class="main-area">
        <div class="main-content">
            <div class="container-fluid">
                @include('partials._provider')
                <div class="card">
                    <div class="card-body p-30">
                        <div class="provider-details-overview mb-30">
                            <div class="provider-details-overview__collect-cash">
                                <div class="statistics-card statistics-card__collect-cash h-100">
                                    <h3>{{ __('messages.collect_cash') }}</h3>
                                        <a href="{{route('providerpayout.create',$providerdata->id)}}" class="btn btn--primary text-capitalize btn--lg mw-75">{{ __('messages.collect') }}</a>
                                </div>
                            </div>
                            <div class="provider-details-overview__statistics">
                                <div class="statistics-card statistics-card__style2 statistics-card__pending-withdraw">
                                    <h2>{{ getPriceFormat($providerData['providerTotEarning']) ?? 0}}</h2>
                                    <h3>{{__('messages.pending_withdraw')}}</h3>
                                </div>

                            <div class="statistics-card statistics-card__style2 statistics-card__already-withdraw">
                                <h2>{{getPriceFormat($providerData['providerTotWithdrableAmt']) ?? 0}}</h2>
                                <h3>{{__('messages.already_withdraw')}}</h3>
                            </div>

                            <div
                                class="statistics-card statistics-card__style2 statistics-card__withdrawable-amount">
                                <h2>{{getPriceFormat($providerData['providerAlreadyWithdrawAmt']) ?? 0}}</h2>
                                <h3>{{__('messages.withdrawble_amount')}}</h3>
                            </div>

                            <div class="statistics-card statistics-card__style2 statistics-card__total-earning">
                                <h2>{{getPriceFormat($providerData['pendWithdrwan']) ?? 0}}</h2>
                                <h3>{{__('messages.total_earning')}}</h3>
                            </div>
                        </div>
                        <div class="provider-details-overview__order-overview">
                            <div class="statistics-card statistics-card__order-overview h-100 pb-2">
                                <h3 class="mb-0">{{__('messages.booking_overview')}}</h3>
                                @if($data['pendingStatusCount']+$data['cancelledstatuscount']+$data['Completedstatuscount']+$data['Acceptedstatuscount'] > 0)
                                <div id="chart" class="d-flex justify-content-center">

                                </div>
                                @else
                                <p style = "color:#009900; font-size:20px;
                                     font-style:italic; text-align:center; margin-top: 20%;">
                                      {{__('messages.nodata')}}
                                    
                                  </p>
                                  @endif
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 310px; height: 234px"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="information-details-box media flex-column flex-sm-row gap-20">
                                <img class="avatar-img radius-5" src="./img/1.png" alt="" />
                                <div class="media-body">
                                    <h2 class="information-details-box__title">
                                        {{ $providerdata->display_name }}
                                    </h2>
                                    <ul class="contact-list">
                                        <li>
                                            <i class="ri-smartphone-line"></i>
                                            <a
                                                href="{{ $providerdata->contact_number }}" class="contact-info-text p-0">{{ !empty($providerdata->contact_number) ? $providerdata->contact_number: '-' }}</a>
                                        </li>
                                        <li>
                                            <i class="ri-mail-line"></i>
                                            <a href="{{ $providerdata->email }}" class="contact-info-text p-0">{{ $providerdata->email }}</a>
                                        </li>
                                        <li>
                                            <i class="ri-map-2-line"></i>
                                            <span class="contact-info-text">{{ !empty($providerdata->address) ?$providerdata->address : '-' }}</span>
                                        </li>
                                    </ul>
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
@section('bottom_script')


    <script type="text/javascript">
        var pendingCount = "{{ $data['pendingStatusCount'] }}";
        var cancelledCount = parseInt("{{ $data['cancelledstatuscount'] }}");
        var Completedcount = parseInt("{{ $data['Completedstatuscount'] }}");
        var Acceptedcount = parseInt("{{ $data['Acceptedstatuscount'] }}");

        var options = {
            series: [parseInt(pendingCount), cancelledCount, Completedcount, Acceptedcount],
            chart: {
                width: 380,
                type: 'pie',
            },
            labels: ['Pending', 'cancell', 'completed', 'accepted'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endsection
</x-master-layout>