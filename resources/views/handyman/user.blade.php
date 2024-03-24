@if(isset($query->id))
@if(auth()->user()->can('handyman edit'))
<a href="{{ route('handyman.create', ['id' => $query->id]) }}">
  <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia($query,'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start">
      <h6 class="m-0">{{ $query->display_name }} </h6>
      <span>{{ $query->email ?? '--' }}</span>
    </div>
  </div>
</a>
@else

 <div class="d-flex gap-3 align-items-center">
    <img src="{{ getSingleMedia($query ,'profile_image', null) }}" alt="avatar" class="avatar avatar-40 rounded-pill">
    <div class="text-start ">
      <h6 class="m-0 btn-link btn-link-hover">{{ $query->display_name }} </h6>
      <span class="btn-link btn-link-hover">{{ $query->email ?? '--' }}</span>
    </div>
  </div>

  @endif
  @else

  <div class="align-items-center">
    <h6 class="text-center">{{ '-' }} </h6>
</div>
@endif




