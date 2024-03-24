@if(isset($query->provider))
  <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia(optional($query->provider),'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start">
      <h6 class="m-0">{{ optional($query->provider)->display_name }} </h6>
      <span>{{ optional($query->provider)->email ?? '--' }}</span>
    </div>
  </div>
  @else

  <div class="align-items-center">
    <h6 class="text-center">{{ '-' }} </h6>
</div>
@endif


