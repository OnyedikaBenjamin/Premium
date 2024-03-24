<x-master-layout>
    {{ Form::open(['route' => ['provider.destroy', $providerdata->id], 'method' => 'delete','data--submit'=>'provider'.$providerdata->id]) }}
    <main class="main-area">
        <div class="main-content">
            <div class="container-fluid">
                @include('partials._provider')
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="review-tab-pane">

                        <div class="card mb-30">
                            <div class="card-body p-30">
                                <div class="row">
                                    <div class="col-lg-5 mb-30 mb-lg-0 d-flex justify-content-center">
                                        <div class="rating-review-wrapper">
                                            <div class="rating-review">
                                                <h2 class="rating-review__title">
                                                    <span class="rating-review__out-of">{{round($providerdata->getServiceRating->avg('rating'), 1)}}</span>{{__('messages./5')}}
                                                </h2>
                                                @php $rating = round($providerdata->getServiceRating->avg('rating'), 1); @endphp
                                                <div class="rating-icons">
                                                    @foreach(range(1,5) as $i)                                               
                                                        <span class="fa-stack" style="width:1em">
                                                            <i class="far fa-star fa-stack-1x"></i>
                                                            @if($rating >0)
                                                            @if($rating >0.5)
                                                            <i class="fas fa-star fa-stack-1x"></i>
                                                            @else
                                                            <i class="fas fa-star-half fa-stack-1x"></i>
                                                            @endif
                                                            @endif
                                                            @php $rating--; @endphp
                                                        </span>                                                
                                                    @endforeach
                                                </div>
                                                <div class="rating-review__info d-flex flex-wrap gap-3">
                                                    <span>{{$providerdata->getServiceRating->sum('rating')}} {{__('messages.rating')}}</span>
                                                    <span>{{count($providerdata->getServiceRating)}} {{__('messages.review')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <ul class="common-list common-list__style2 rating-progress after-none gap-10">
                                            <li class="excellent">
                                                <span class="review-name">{{__('messages.excellent')}}</span>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ $providerdata->getServiceRating->where('rating','5.0')->count('rating') }}%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="review-count">{{ $providerdata->getServiceRating->where('rating','5.0')->count('rating') }}</span>
                                            </li>
                                            <li class="good">
                                                <span class="review-name">{{__('messages.good')}}</span>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $providerdata->getServiceRating->where('rating','4.0')->count('rating') }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="review-count">{{ $providerdata->getServiceRating->where('rating','4.0')->count('rating') }}</span>
                                            </li>
                                            <li class="avarage">
                                                <span class="review-name">{{__('messages.avarage')}}</span>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $providerdata->getServiceRating->where('rating','3.0')->count('rating') }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="review-count">{{ $providerdata->getServiceRating->where('rating','3.0')->count('rating') }}</span>
                                            </li>
                                            <li class="below-avarage">
                                                <span class="review-name">{{__('messages.below_avarage')}}</span>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width:  {{ $providerdata->getServiceRating->where('rating','2.0')->count('rating') }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="review-count">{{ $providerdata->getServiceRating->where('rating','2.0')->count('rating') }}</span>
                                            </li>
                                            <li class="poor">
                                                <span class="review-name">{{__('messages.poor')}}</span>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width:  {{ $providerdata->getServiceRating->where('rating','1.0')->count('rating') }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="review-count">{{ $providerdata->getServiceRating->where('rating','1.0')->count('rating') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                    <form action="#" class="search-form search-form_style-two" method="POST">
                                        <input type="hidden" name="_token" value="2Z3p3p2QpaleoE8kL1grkdIdf9Qq3t6XoSCzwYyC" />
                                        
                                        
                                    </form>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-block card-stretch">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{__('messages.Review')}}</h5>
                                                            <div class="table-responsive">
                                                                <table class="table data-table mb-0">
                                                                    <thead class="table-color-heading">
                                                                        <tr class="text-secondary">
                                                                            <th scope="col">{{__('messages.booking_id')}}</th>
                                                                            <th scope="col">{{__('messages.booking_date')}}</th>
                                                                            <th scope="col">{{__('messages.rating')}}</th>
                                                                            <th scope="col">{{__('messages.review')}}</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end"></div>
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
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'date',
                    name: 'date'

                },
                {
                    data: 'rating',
                    name: 'rating'
                },
                {
                    data: 'review',
                    name: 'review'
                },
            ]
        });
    </script>
    @endsection
</x-master-layout>

