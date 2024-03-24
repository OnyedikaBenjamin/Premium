<x-master-layout>
    {{ Form::open(['route' => ['provider.destroy', $providerdata->id], 'method' => 'delete','data--submit'=>'provider'.$providerdata->id]) }}
    <main class="main-area">
        <div class="main-content">
            <div class="container-fluid">
                @include('partials._provider')
                <div class="card">
                    <div class="card-body p-30">
                        <div class="service-man-list">
                            @foreach($providerdata->providerHandyman as $handyman)
                            <div class="service-man-list__item">
                                <div class="service-man-list__item_header">
                                    <div class="attach-img-box position-relative">
                                        @php
                                        $extention = imageExtention(getSingleMedia($providerdata,'profile_image'));
                                        @endphp
                                        <img id="profile_image_preview" src="{{getSingleMedia($providerdata,'profile_image')}}" alt="#" class="attachment-image mt-1" style="background-color:{{ $extention == 'svg' ? $providerdata->color : '' }}">
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $providerdata->id, 'type' => 'profile_image']) }}" data--submit="confirm_form" data--confirmation='true' data--ajax="true" title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}' data-title='{{ __("messages.remove_file_title" , ["name" =>  __("messages.image") ]) }}' data-message='{{ __("messages.remove_file_msg") }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                    </div>
                                    <h4 class="service-man-name">{{$handyman->display_name ?? '-' }}</h4>
                                    <a class="service-man-phone" href="{{$handyman->contact_number}}">{{$handyman->contact_number ?? '-' }}</a>
                                </div>
                                <div class="service-man-list__item_body">
                                    <a class="service-man-mail" href="{{$handyman->email}}">{{$handyman->email ?? '-' }}</a>
                                    <p class="service-man-address">{{$handyman->address ?? '-' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{ Form::close() }}
    @section('bottom_script')
    @endsection
</x-master-layout>