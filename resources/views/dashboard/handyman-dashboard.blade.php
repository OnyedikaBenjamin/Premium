<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('booking.index') }}">
                            <div class="card total-booking-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                <h4 class="mb-2 booking-text  font-weight-bold">{{ !empty($data['dashboard']['count_total_booking']) ? $data['dashboard']['count_total_booking']: 0 }} </h4>
                                            </div>
                                            <p class="mb-0 booking-text">{{ __('messages.total_name', ['name' => __('messages.booking')]) }}</p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center flex-column">
                                            <div class="iq-card-icon iq-card-icon-booking icon-shape  rounded-circle shadow">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.52083 14.7917L6.5625 12.8542C6.4375 12.7292 6.375 12.5799 6.375 12.4062C6.375 12.2326 6.4375 12.0764 6.5625 11.9375C6.67361 11.8264 6.82292 11.7708 7.01042 11.7708C7.19792 11.7708 7.35417 11.8264 7.47917 11.9375L9.02083 13.4583L12.3958 10.0625C12.5347 9.93749 12.691 9.87846 12.8646 9.88541C13.0382 9.89235 13.1875 9.95832 13.3125 10.0833C13.4236 10.2222 13.4826 10.3785 13.4896 10.5521C13.4965 10.7257 13.4375 10.875 13.3125 11L9.52083 14.7917C9.38195 14.9305 9.21528 15 9.02083 15C8.82639 15 8.65972 14.9305 8.52083 14.7917ZM3.79167 18.4583C3.41667 18.4583 3.08681 18.316 2.80208 18.0312C2.51736 17.7465 2.375 17.4167 2.375 17.0417V4.20832C2.375 3.81943 2.51736 3.4861 2.80208 3.20832C3.08681 2.93055 3.41667 2.79166 3.79167 2.79166H5.10417V2.24999C5.10417 2.05555 5.17361 1.88888 5.3125 1.74999C5.45139 1.6111 5.61806 1.54166 5.8125 1.54166C6.02083 1.54166 6.19444 1.6111 6.33333 1.74999C6.47222 1.88888 6.54167 2.05555 6.54167 2.24999V2.79166H13.4583V2.24999C13.4583 2.05555 13.5278 1.88888 13.6667 1.74999C13.8056 1.6111 13.9722 1.54166 14.1667 1.54166C14.375 1.54166 14.5486 1.6111 14.6875 1.74999C14.8264 1.88888 14.8958 2.05555 14.8958 2.24999V2.79166H16.2083C16.5972 2.79166 16.9306 2.93055 17.2083 3.20832C17.4861 3.4861 17.625 3.81943 17.625 4.20832V17.0417C17.625 17.4167 17.4861 17.7465 17.2083 18.0312C16.9306 18.316 16.5972 18.4583 16.2083 18.4583H3.79167ZM3.79167 17.0417H16.2083V8.12499H3.79167V17.0417ZM3.79167 6.87499H16.2083V4.20832H3.79167V6.87499ZM3.79167 6.87499V4.20832V6.87499Z" fill="white"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a  href="{{ route('service.index') }}">
                            <div class="card total-service-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                <h4 class="mb-2 booking-text font-weight-bold">{{ !empty($data['dashboard']['count_handyman_pending_booking']) ? $data['dashboard']['count_handyman_pending_booking'] : 0 }}</h4>
                                            </div>
                                            <p class="mb-0 booking-text">{{ __('messages.total_name', ['name' => __('messages.pending')]) }}</p>
                                        </div>
                                        <div class="col-auto d-flex flex-column">
                                            <div class="iq-card-icon iq-card-icon-service icon-shape  text-white rounded-circle shadow">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14.4583 18.3333C13.375 18.3333 12.4479 17.9513 11.6771 17.1875C10.9063 16.4236 10.5208 15.5 10.5208 14.4166C10.5208 13.3194 10.9063 12.3854 11.6771 11.6145C12.4479 10.8437 13.375 10.4583 14.4583 10.4583C15.5556 10.4583 16.4861 10.8437 17.25 11.6145C18.0139 12.3854 18.3958 13.3125 18.3958 14.3958C18.3958 15.493 18.0139 16.4236 17.25 17.1875C16.4861 17.9513 15.5556 18.3333 14.4583 18.3333ZM15.6875 16.5416L16.2917 15.9166L14.7292 14.3541V12H13.8542V14.6666L15.6875 16.5416ZM3.79167 17.3541C3.40278 17.3541 3.06944 17.2152 2.79167 16.9375C2.51389 16.6597 2.375 16.3194 2.375 15.9166V3.89579C2.375 3.5069 2.5 3.1701 2.75 2.88538C3 2.60065 3.22222 2.45829 3.41667 2.45829H7.47917C7.59028 1.97218 7.84722 1.57288 8.25 1.26038C8.65278 0.947876 9.11111 0.791626 9.625 0.791626C10.1389 0.791626 10.5938 0.947876 10.9896 1.26038C11.3854 1.57288 11.6458 1.97218 11.7708 2.45829H15.8333C16.2222 2.45829 16.5556 2.60065 16.8333 2.88538C17.1111 3.1701 17.25 3.5069 17.25 3.89579V10.0416C17.0278 9.88885 16.7986 9.76038 16.5625 9.65621C16.3264 9.55204 16.0833 9.46524 15.8333 9.39579V3.89579H13.625V6.60413H6V3.89579H3.79167V15.9166H9.5C9.56944 16.1666 9.6632 16.4097 9.78125 16.6458C9.89931 16.8819 10.0486 17.118 10.2292 17.3541H3.79167ZM10 3.74996C10.2361 3.74996 10.434 3.6701 10.5938 3.51038C10.7535 3.35065 10.8333 3.15274 10.8333 2.91663C10.8333 2.68051 10.7535 2.4826 10.5938 2.32288C10.434 2.16315 10.2361 2.08329 10 2.08329C9.76389 2.08329 9.56597 2.16315 9.40625 2.32288C9.24653 2.4826 9.16667 2.68051 9.16667 2.91663C9.16667 3.15274 9.24653 3.35065 9.40625 3.51038C9.56597 3.6701 9.76389 3.74996 10 3.74996Z" fill="white"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a  href="{{ route('handyman.index') }}">
                            <div class="card total-provider-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                <h4 class="mb-2 booking-text font-weight-bold">{{ !empty($data['dashboard']['count_handyman_complete_booking']) ? $data['dashboard']['count_handyman_complete_booking'] : 0 }}</h4>
                                                <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                            </div>
                                            <p class="mb-0 booking-text">{{ __('messages.complete', ['name' => __('messages.booking')]) }}</p>
                                        </div>
                                        <div class="col-auto d-flex flex-column">
                                            <div class="iq-card-icon iq-card-icon-provider icon-shape  text-white rounded-circle shadow">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.79167 17.625C2.41667 17.625 2.08681 17.4826 1.80208 17.1979C1.51736 16.9132 1.375 16.5833 1.375 16.2083V3.79167C1.375 3.40278 1.51736 3.06944 1.80208 2.79167C2.08681 2.51389 2.41667 2.375 2.79167 2.375H17.2083C17.5972 2.375 17.9306 2.51389 18.2083 2.79167C18.4861 3.06944 18.625 3.40278 18.625 3.79167V16.2083C18.625 16.5833 18.4861 16.9132 18.2083 17.1979C17.9306 17.4826 17.5972 17.625 17.2083 17.625H2.79167ZM2.79167 16.2083H17.2083V3.79167H2.79167V16.2083ZM4.3125 14.1667H8.4375V12.5H4.3125V14.1667ZM12.125 12.5L16.2292 8.39583L15.0208 7.20833L12.125 10.1458L10.9375 8.95833L9.77083 10.1458L12.125 12.5ZM4.3125 10.8333H8.4375V9.16667H4.3125V10.8333ZM4.3125 7.5H8.4375V5.83333H4.3125V7.5ZM2.79167 16.2083V3.79167V16.2083Z" fill="white"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a  href="{{ route('payment.index') }}">
                            <div class="card total-revenue">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                                                <h4 class="mb-2 booking-text font-weight-bold">{{ getPriceFormat(round($data['total_revenue'])) }}</h4>
                                                <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                            </div>
                                            <p class="mb-0 booking-text">{{ __('messages.total_name', ['name' => __('messages.revenue')]) }}</p>
                                        </div>
                                        <div class="col-auto d-flex flex-column">
                                            <div class="iq-card-icon iq-card-icon-revenue icon-shape text-white rounded-circle shadow">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.37508 15.9792H10.5626V14.9167C11.4098 14.8194 12.0695 14.559 12.5417 14.1354C13.014 13.7118 13.2501 13.1389 13.2501 12.4167C13.2501 11.7083 13.0487 11.1285 12.6459 10.6771C12.2431 10.2257 11.5556 9.7986 10.5834 9.39582C9.79175 9.06249 9.21536 8.76388 8.85425 8.49999C8.49314 8.2361 8.31258 7.88193 8.31258 7.43749C8.31258 7.02082 8.46536 6.69096 8.77091 6.44791C9.07647 6.20485 9.49314 6.08332 10.0209 6.08332C10.4376 6.08332 10.7987 6.18055 11.1042 6.37499C11.4098 6.56943 11.6667 6.86805 11.8751 7.27082L12.9376 6.77082C12.6876 6.25693 12.3716 5.8611 11.9897 5.58332C11.6077 5.30555 11.1459 5.13888 10.6042 5.08332V4.04166H9.41675V5.08332C8.69453 5.18055 8.13203 5.44443 7.72925 5.87499C7.32647 6.30555 7.12508 6.83332 7.12508 7.45832C7.12508 8.15277 7.33689 8.70485 7.7605 9.11457C8.18411 9.52429 8.82647 9.90277 9.68758 10.25C10.5904 10.625 11.2119 10.9687 11.5522 11.2812C11.8924 11.5937 12.0626 11.9722 12.0626 12.4167C12.0626 12.8611 11.882 13.2153 11.5209 13.4792C11.1598 13.743 10.7015 13.875 10.1459 13.875C9.60425 13.875 9.12508 13.7222 8.70841 13.4167C8.29175 13.1111 8.00008 12.6875 7.83341 12.1458L6.70842 12.5208C7.00008 13.1736 7.36467 13.6875 7.80217 14.0625C8.23966 14.4375 8.76397 14.7083 9.37508 14.875V15.9792ZM10.0001 18.4583C8.8473 18.4583 7.75355 18.2361 6.71883 17.7917C5.68411 17.3472 4.7848 16.743 4.02091 15.9792C3.25703 15.2153 2.65286 14.3194 2.20841 13.2917C1.76397 12.2639 1.54175 11.1667 1.54175 9.99999C1.54175 8.83332 1.76397 7.73263 2.20841 6.69791C2.65286 5.66318 3.25703 4.76735 4.02091 4.01041C4.7848 3.25346 5.68064 2.65277 6.70842 2.20832C7.73619 1.76388 8.83341 1.54166 10.0001 1.54166C11.1667 1.54166 12.2674 1.76388 13.3022 2.20832C14.3369 2.65277 15.2327 3.25346 15.9897 4.01041C16.7466 4.76735 17.3473 5.65971 17.7917 6.68749C18.2362 7.71527 18.4584 8.81943 18.4584 9.99999C18.4584 11.1667 18.2362 12.2639 17.7917 13.2917C17.3473 14.3194 16.7466 15.2153 15.9897 15.9792C15.2327 16.743 14.3404 17.3472 13.3126 17.7917C12.2848 18.2361 11.1806 18.4583 10.0001 18.4583ZM10.0001 17.0417C11.9584 17.0417 13.6216 16.3542 14.9897 14.9792C16.3577 13.6042 17.0417 11.9444 17.0417 9.99999C17.0417 8.04166 16.3577 6.37846 14.9897 5.01041C13.6216 3.64235 11.9584 2.95832 10.0001 2.95832C8.05564 2.95832 6.39591 3.64235 5.02091 5.01041C3.64591 6.37846 2.95841 8.04166 2.95841 9.99999C2.95841 11.9444 3.64591 13.6042 5.02091 14.9792C6.39591 16.3542 8.05564 17.0417 10.0001 17.0417Z" fill="white"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <div id='calendar'></div>
                </div>
              </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    @section('bottom_script')
    <script>
    if (jQuery('#calendar').length) {
      document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

        var today = new Date().toISOString().slice(0, 10);


    
      var calendar = new FullCalendar.Calendar(calendarEl, {

      plugins: [ 'dayGrid','timeGrid', 'dayGrid', 'list','interaction','bootstrap' ],
      defaultView: 'dayGridMonth',
      displayEventTime: true,
      themeSystem: 'bootstrap',
      header: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
          clear: ''
      },
      height : 600,
      selectable: true,
      selectHelper: true,
      editable: true,
      eventLimit: false,
      showNonCurrentDates : false,
      droppable: false,
      editable:false,
      eventSources:[{
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                url:  "{{ route('home') }}",
                dataType: 'JSON',
                data: {
                    start: info['startStr'],
                    end: info['endStr'],
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    successCallback(response);
                    return response;
                },
                failure: function(data){
                    failureCallback(data);
                }
            });
        },
        color  : "rgb(19, 193, 240)",
        textColor : "#fff",
        eventDataTransform: function(eventData) {

            console.log(eventData);
          return {
              id: eventData.id,
              title: (eventData.service != null && eventData.service != '') ? eventData.service.name : '-' ,
              start: eventData.date,
          };
        },
      }],
      eventRender: function (event, element, view) {
          if (event.allDay === 'true') {
              event.allDay = true;
          } else {
              event.allDay = false;
          }
      },
      eventDrop: function(info) {},
      eventClick:  function(info) {
        var id = info.event.id;
        var url = "{{ URL::to('booking') }}/"+id;
        window.location.replace(url);
      },

    });
    calendar.render();
    });
    }
</script>
<style>

.fc-today .fc-day-number{
 
  font-size: 23px !important;
  font-weight:700 !important;
  
} 

.fc-today{
   
    display: block !important;
}
.fc-list-item-title a{

    cursor: pointer !important;
}


</style>
    @endsection
</x-master-layout>

