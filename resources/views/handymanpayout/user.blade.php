@if(isset($payout->handymans))
  <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia(optional($payout->handymans),'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start">
      <h6 class="m-0">{{ optional($payout->handymans)->first_name }} {{ optional($payout->handymans)->last_name }}</h6>
      <span>{{ optional($payout->handymans)->email ?? '--' }}</span>
    </div>
  </div>

  @else

  <div class="align-items-center">
    <h6 class="text-center">{{ '-' }} </h6>
</div>
@endif



