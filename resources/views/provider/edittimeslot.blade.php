<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="font-weight-bold">{{$provider->first_name .' '. $provider->last_name}} {{$pageTitle}}</h5>
                            <a href="{{ route('provider.time-slot',['id' => $provider_id]) }}   " class="float-right btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    {{ Form::model($slotsArray, ['method' => 'POST', 'route' => 'providerslot.store', 'data-toggle' => 'validator', 'id' => 'provider-form']) }}
                        <div class="row">
                            <div class="col-md-12">
                            <input type="hidden" name="id" id="provider-id" value="{{ $provider_id }}">
                                <div class="form-group has-feedback">
                                            {{ Form::label('Day', __('messages.day').' <span class="text-danger">*</span>', ['class' => 'form-control-label col-md-12'], false) }}
                                            <div class="col-md-12">
                                            
                                                <ul class="nav nav-tabs nav-fill gap-3 tabslink" id="tab-text" role="tablist">
                                                    @foreach ($slotsArray['days'] as $day)
                                                        @if (isset($day))
                                                            <li class="nav-item m-0">
                                                                <a href="#{{ $day }}" name="days" class="nav-link day-link" data-day="{{ $day }}" data-bs-toggle="tab" rel="tooltip">{{ ucfirst($day) }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <div class="col-md-12">
                                                {{ Form::label('Time', __('messages.time').' <span class="text-danger">*</span>', ['class' => 'form-control-label col-md-12'], false) }}
                                                <div class="tab-content" id="pills-tabContent-1">
                                                    @foreach ($slotsArray['days'] as $day)
                                                        @if (isset($day))
                                                            <div class="tab-pane p-1 day-slot @if(strtolower($day) === strtolower($activeDay)) active @endif" id="{{ $day }}">
                                                                <!-- <h3>{{ ucfirst($day) }}</h3> -->
                                                                <ul class="nav nav-tabs nav-fill tabslink gap-3 provider-slot">
                                                                    @for ($hour = 0; $hour < 24; $hour++)
                                                                        <li class="nav-item m-0">
                                                                            @php
                                                                                $slotTime = sprintf('%02d:00', $hour);
                                                                                $isActive = in_array($slotTime, $activeSlots[$day] ?? []);
                                                                            @endphp
                                                                            <a href="javascript:void(0)" name="start_at" class="nav-link time-link @if ($isActive) active @endif slot-link" data-day="{{ $day }}" data-slot="{{ $slotTime }}" data-bs-toggle="tab" rel="tooltip">{{ $slotTime }}</a>
                                                                        </li>
                                                                    @endfor
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                {{ Form::submit(__('messages.submit'), ['class' => 'btn btn-md btn-primary']) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
       
        setActiveDay('mon');
        var urlParams = new URLSearchParams(window.location.search);
        var provider_id = urlParams.get('id');
        
        $('.day-link').on('click', function (e) {
            e.preventDefault();
            var selectedDay = $(this).data('day');
            setActiveDay(selectedDay);
            showActiveDaySlots();
        });

        function setActiveDay(day) {
            $('.day-slot').removeClass('active');
            $('.day-link').removeClass('active');
            $('.day-link[data-day="' + day + '"]').addClass('active');
            $('.day-slot#' + day).addClass('active');
            activeDay = day;
        }

        function showActiveDaySlots() {
            $('.day-slot').hide();
            $('.day-slot.active').show();
        }

        $('.time-link').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
        });

        function showMessage(message) {
            Snackbar.show({
                text: message,
                pos: 'bottom-center'
            });
        }

        $('#provider-form').on('submit', function (e) {
            e.preventDefault();
            var selectedSlots = [];
            var selectedSlotsByDay = {};

            $('.slot-link.active').each(function () {
                var day = $(this).data('day');
                var slot = $(this).data('slot');

                if (!(day in selectedSlotsByDay)) { 
                    selectedSlotsByDay[day] = [];
                }

                selectedSlotsByDay[day].push(slot);
            });
            for (var day in selectedSlotsByDay) {
                selectedSlots.push({
                    day: day,
                    time: selectedSlotsByDay[day]
                });
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route("providerslot.store") }}', 
                data: {provider_id: provider_id, slots: selectedSlots },
                success: function (response) {
                    console.log(response);
                    showMessage(response.message);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>
