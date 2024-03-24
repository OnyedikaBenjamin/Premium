@if(isset($service->serviceBooking->first()->customer))
  <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia(optional($service->serviceBooking->first()->customer),'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start">
      <h6 class="m-0">{{ optional($service->serviceBooking->first()->customer)->display_name}}</h6>
      <span>{{ optional($service->serviceBooking->first()->customer)->email ?? '--' }}</span>
    </div>
  </div>
  @else

  <div class="align-items-center">
    <h6 class="text-center">{{ '-' }} </h6>
</div>
@endif




