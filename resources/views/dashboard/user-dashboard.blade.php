<x-master-layout>
    <div class="container-fluid">
        <div class="row">
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
    @endsection
</x-master-layout>

