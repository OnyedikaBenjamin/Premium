<div class="page-title-wrap mb-3 p-3">
    <h2 class="page-title">{{__('messages.provider_detail')}}</h2>
</div>


<div class="mb-3 ms-2">
    <ul class="nav nav--tabs nav--tabs__style2 provider-detail-tab">
        <li class="nav-item {{request()->routeIs('provider.show') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('provider.show',$providerdata->id) }}"> {{__('messages.overview')}}</a>
        </li>
        <li class="nav-item {{request()->routeIs('service.show') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('service.show',$providerdata->id) }}"> {{__('messages.plan')}}</a>
        </li>
        <li class="nav-item {{request()->routeIs('booking.details') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('booking.details',$providerdata->id) }}"> {{__('messages.Bookings')}}</a>
        </li>
        <li class="nav-item {{request()->routeIs('handyman.show') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('handyman.show',$providerdata->id) }}">{{__('messages.handyman')}}</a>
        </li>
        <li class="nav-item {{request()->routeIs('setting.comission') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('setting.comission',$providerdata->id) }}">{{__('messages.Settings')}}</a>
        </li>
        <li class="nav-item {{request()->routeIs('bank.show') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('bank.show',$providerdata->id) }}">{{__('messages.Bank_info')}}</a>
        </li>
        <li class="nav-item {{request()->routeIs('provider.review') ? 'active' : ''}}">
            <a class="nav-link" href="{{ route('provider.review',$providerdata->id) }}">{{__('messages.Reviews')}}</a>
        </li>
    </ul>
</div>