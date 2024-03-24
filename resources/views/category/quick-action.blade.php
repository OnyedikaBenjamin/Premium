<form action="{{$url ?? ''}}" id="quick-action-form" class="form-disabled d-flex gap-3 align-items-center">
    @csrf
    {{$slot}}
    <button class="btn btn-success">Apply</button>
  </form>
  