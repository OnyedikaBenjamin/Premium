@if(isset($provider_id))
<a href="{{ route('earning.show', ['id' => $provider_id]) }}">
  <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia(optional($row),'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start">
      <h6 class="m-0">{{ $provider_name}}</h6>
      <span>{{ $email ?? '--' }}</span>
    </div>
  </div>
</a>
@else

<div class="align-items-center">
    <h6 class="text-center">{{ '-' }} </h6>
</div>
@endif




