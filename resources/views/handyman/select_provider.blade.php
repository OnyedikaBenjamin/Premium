 <select name="provider" class="form-control select2 change-select" data-token="{{csrf_token()}}" data-id="{{ $handyman->id }}">
    <option value="Null">Select</option>
  @foreach ($provider as $key => $value )
    <option value="{{$value->id}}" {{$handyman->id == $value->id ? 'selected' : ''}}>{{$value->first_name.' '.$value->last_name}}</option>
  @endforeach
</select>
 

<script>
$(document).on('change', '.change-select', function() {

    var provider_id = $(this).val();

    var id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('handyman.updateProvider') }}",
        data: {
            'provider_id': provider_id,
            'id': id
        },
        success: function(data) {
            Snackbar.show({ text: data.message, pos: 'bottom-center', duration: 10000 })
            $('#dataTableBuilder').DataTable().clear().draw();
        }
    });
})
</script>