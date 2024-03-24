@if(isset($query->id))
<a href="{{ route('provider.show', ['provider' => $query->id]) }}">
  <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia($query,'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start">
      <h6 class="m-0">{{ $query->first_name }} {{ $query->last_name }}</h6>
      <span>{{ $query->email ?? '--' }}</span>
    </div>
  </div>
</a>
@else

<div class="align-items-center">
    <h6 class="text-center">{{ '-' }} </h6>
</div>
@endif



