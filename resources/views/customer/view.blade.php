<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-block card-stretch">
                                <div class="card-body p-0">
                                    <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                                        <h5 class="font-weight-bold">{{$pageTitle}}</h5>
                                        <a href="{{ route('user.index') }}   " class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card card-block card-stretch">
                                <div class="card-body">
                                <h5 class="card-title">{{__('messages.payment_details')}}</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="table-color-heading">
                                                <tr class="text-secondary">
                                                <th scope="col">{{__('messages.service')}}</th>
                                                <th scope="col">{{__('messages.date')}}</th>
                                                <th scope="col">{{__('messages.status')}}</th>
                                                <th scope="col">{{__('messages.provider')}}</th>
                                                <th scope="col" class="text-right">{{__('messages.total_amount')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($customerdata->booking) > 0)
                                                    @foreach($customerdata->booking as $booking)
                                                        <tr class="white-space-no-wrap">
                                                            <td>{{optional($booking->service)->name}}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                            
                                                                    <div>{{date("D, d M Y", strtotime($booking->date))}}</div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0  d-flex justify-content-start align-items-center">
                                                                    
                                                                    {{str_replace("_"," ",ucfirst($booking->status))}}
                                                                </p>
                                                            </td>
                                                            <td>{{optional($booking->provider)->display_name ?? '-' }}</td>
                                                            <td class="text-right">{{getPriceFormat($booking->total_amount ?? '0') }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center font-weight-bold">{{__('messages.record_not_found')}}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card card-block card-stretch">
                                <div class="card-body">
                                    <h5 class="card-title">{{__('messages.pending_trans')}}</h5>
                                    <div class="table-responsive-sm">
                                        <table class="table mb-0">
                                            <thead class="table-color-heading">
                                                <tr class="text-secondary">
                                                    <th scope="col">{{__('messages.service')}}</th>
                                                    <th scope="col">{{__('messages.date')}}</th>
                                                    <th scope="col">{{__('messages.payment_status')}}</th>
                                                    <th scope="col" class="text-right">{{__('messages.total_amount')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($customer_pending_trans) > 0)
                                                    @foreach($customer_pending_trans as $pending)
                                                        <tr class="white-space-no-wrap">
                                                            <td>{{$pending->booking->service->name}}</td>
                                                            <td> <div class="d-flex align-items-center">{{date("D, d M Y", strtotime($pending->booking->date))}}</div></td>
                                                            <td>{{ $pending->payment_status}}</td>
                                                            <td class="text-right">{{ getPriceFormat($pending->total_amount)}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4" class="text-center font-weight-bold">{{__('messages.record_not_found')}}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            <div class="card card-block p-card">
                                <div class="profile-box">
                                    <div class="profile-card rounded">
                                        <img src="{{ getSingleMedia($customerdata,'profile_image') }}" alt="profile-bg" class="avatar-100 d-block mx-auto img-fluid mb-3  avatar-rounded">
                                        <h3 class="font-600 text-white text-center mb-5">{{$customerdata->display_name}}</h3>
                                    </div>
                                    <div class="pro-content rounded">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-icon mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-0 eml">{{$customerdata->email}}</p>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-icon mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-0">{{$customerdata->contact_number}}</p>
                                        </div>
                                        @if(!empty($customerdata->address))
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="p-icon mr-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-0">{{$customerdata->address}}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-master-layout>