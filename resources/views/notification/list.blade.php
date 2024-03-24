<div class="card-header-border card-header">
    <div class="card-notify-box">
        <h4 class="text-center notify-title">
            {{ __('messages.all_notifications') }}
        </h4>
        <small class="badge badge-light float-right notification_count notification_tag"> {{ $all_unread_count }}</small>
    </div>  
    <div class="read-notify-box">
        <h6 class="text-sm text-muted m-0"><span class="notification_count">{{ __('messages.you_unread_notification',['number' => $all_unread_count  ]) }}</span>
            @if($all_unread_count > 0 )
                <a href="#" data-type="markas_read" class="notifyList pull-right" ><span>{{__('messages.mark_all_as_read') }}</span></a>
            @endif
        </h6>
    </div> 
</div>

<div class="card-body overflow-auto card-header-border p-0 card-body-list">
    <ul class="dropdown-menu-1 overflow-y-auto list-style-1 mb-0 notification-height">
        @if(isset($notifications) && count($notifications) > 0)
            @foreach($notifications->sortByDesc('created_at')->take(5) as $notification)
                <li class="dropdown-item-1 float-none p-3 {{ $notification->read_at ? '':'notify-list-bg'}} ">
                    <a href="{{ route('booking.show', $notification->data['id']) }}" class="">
                    <div class="list-item d-flex justify-content-start align-items-start">
                        <div class="list-style-detail ml-2 mr-2">
                            <h6 class="font-weight-bold mb-1"># {{ $notification->data['id'] ." ". str_replace("_"," ",ucfirst($notification->data['type'])) }}</h6>
                            <p class="mb-1">
                                <small class="text-secondary">{{ isset($notification->data['message']) ? $notification->data['message'] : __('messages.booking') }}</small>
                            </p>
                            <p class="m-0">
                                <small class="text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mr-1" width="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ timeAgoFormate($notification->created_at) }}
                                </small>
                            </p>
                        </div>
                    </div>                                                
                </a>
                </li>
            @endforeach
        @else
            <li class="dropdown-item-1 float-none p-3">
                <div class="list-item d-flex justify-content-center align-items-center">
                    <div class="list-style-detail ml-2 mr-2">
                    <h6 class="font-weight-bold">{{ __('messages.no_notification') }}</h6>
                    <p class="mb-0"></p>
                    </div>
                </div>
            </li>
        @endif
    </ul>
</div>
@if(isset($notifications) && count($notifications) > 0)
<div class="card-footer text-muted p-3 text-center ">
    <a href="{{ route('notification.index') }}" class="mb-0 btn-link btn-link-hover font-weight-bold view-all-btn">{{ __('messages.view_all') }}</a>
</div>
@endif
<script>
     $('.notifyList').on('click',function(){
        notificationList($(this).attr('data-type'));
    });

    $(document).on('click','.notification_data',function(event){
        event.stopPropagation();
    })

    function notificationList(type=''){
        var url = "{{ route('notification.list') }}";
        $.ajax({
            type: 'get',
            url: url,
            data: {'type':type},
            success: function(res){

                $('.notification_data').html(res.data);
                getNotificationCounts();
                if(res.type == "markas_read"){
                    notificationList();
                }
                $('.notify_count').removeClass('notification_tag').text('');
            }
        });
    }

    function getNotificationCounts(){
        var url = "{{ route('notification.counts') }}";
        $.ajax({
            type: 'get',
            url: url,
            success: function(res){
                if(res.counts > 0){
                    $('.notify_count').addClass('notification_tag').text(res.counts);
                    setNotification(res.counts);
                    $('.notification_list span.dots').addClass('d-none')
                    $('.notify_count').removeClass('d-none')
                }else{
                    $('.notify_count').addClass('d-none')
                    $('.notification_list span.dots').removeClass('d-none')
                }

                if(res.counts <= 0 && res.unread_total_count > 0){
                    $('.notification_list span.dots').removeClass('d-none')
                }else{
                    $('.notification_list span.dots').addClass('d-none')
                }
            }
        });
    }
</script>