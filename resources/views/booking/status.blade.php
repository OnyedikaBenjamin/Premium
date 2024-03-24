@php
$extraValue = 0;
@endphp
<div class="border-bottom pb-3">
    <div class="row pb-1 gy-2">
        <div class="col-6 col-lg-3">
            <div>
                <h4 class="c1 mb-2 pb-1">{{__('messages.book_placed')}}</h4>
                <p class="opacity-75">{{ $bookingdata->created_at}}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div>
                <h4 class="c1  mb-2 pb-1">{{__('messages.booking_status')}}</h4>
                <p class="opacity-75">{{  App\Models\BookingStatus::bookingStatus($bookingdata->status)}}</p>
                <!-- <p class="opacity-75">{{ $bookingdata->status}}</p> -->
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div>
                <h4 class="c1  mb-2 pb-1">{{__('messages.payment_status')}}</h4>
                <p class="opacity-75">{{ ucwords(str_replace('_', ' ',  optional($bookingdata->payment)->payment_status ?: 'pending'))}}</p>
                <!-- <p class="opacity-75">{{ optional($bookingdata->payment)->payment_status ?: 'pending' }}</p> -->
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div>
                <h4 class="c1  mb-2 pb-1">{{__('messages.booking_amount')}}</h4>
                <p class="opacity-75">{{!empty($bookingdata->total_amount) ? getPriceFormat($bookingdata->total_amount + $extraValue ): 0}}</p>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-between mt-5">
    <div class="col-md-6 col-xl-4 d-flex justify-content-center customer-info-detail">
        <div class="d-flex flex-column gap-30 w-100">
            @if($bookingdata->handymanAdded->count() !== 0 )
            <div class="c1-light-bg radius-10 py-3 px-3">
               
                    @foreach($bookingdata->handymanAdded as $booking)
                    <h4 class="mb-2">{{__('messages.handyman_information')}}</h4>
                    <h5 class="c1 mb-3">{{optional($booking->handyman)->display_name ?? '-'}}</h5>
                    <ul class="list-info">
                        <li>
                            <span class="material-icons customer-info-text">{{__('messages.phone_information')}}</span>
                            <a href="" class="customer-info-value">
                                <p class="mb-0">{{optional($booking->handyman)->contact_number ?? '-'}}</p>
                            </a>
                        </li>
                        <li>
                            <span class="material-icons customer-info-text">{{__('messages.address')}}</span>
                            <p class="customer-info-value">{{optional($booking->handyman)->address ?? '-'}}</p>
                        </li>
                    </ul>
                    @endforeach
             
            </div>
            @endif
            <div class="c1-light-bg radius-10 py-3 px-3">
                <h4 class="mb-2">{{__('messages.provider_information')}}</h4>
                <h5 class="c1 mb-3">{{optional($bookingdata->provider)->display_name ?? '-'}}</h5>
                <ul class="list-info">
                    <li>
                        <span class="material-icons customer-info-text">{{__('messages.phone_information')}}</span>
                        <a href="" class="customer-info-value">
                            <p class="mb-0">{{ optional($bookingdata->provider)->contact_number ?? '-' }}</p>
                        </a>
                    </li>
                    <li>
                        <span class="material-icons customer-info-text">{{__('messages.address')}}</span>
                        <p class="customer-info-value">{{ optional($bookingdata->provider)->address ?? '-' }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4 mb-5 mb-md-0">
        @if(count($bookingdata->bookingActivity) > 0)
        <div class="col-md-5 col-lg-12">
            <div class="card">
                <div class="card-body activity-height">
                    <ul class="iq-timeline">
                        <?php date_default_timezone_set($admin->time_zone ?? 'UTC'); ?>
                        @foreach($bookingdata->bookingActivity as $activity)
                        <li>
                            <div class="timeline-dots"></div>
                            <div class="d-flex justify-content-between gap-2">
                            <h6 class="mb-1">{{str_replace("_"," ",ucfirst($activity->activity_type))}}</h6>
                            <small class="mb-1">{{$activity->datetime}}</small>
                            </div>
                            <div class="d-inline-block w-100">
                                <p>{{$activity->activity_message}}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>