<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                        </div>
                        
                    </div>
                </div>
                <div class="card">
                        <div class="card-body">
                            <div class="float-right ">
                                <div class="d-flex justify-content-end">
                                    
                                    <div class="input-group ml-auto">
                                        <span class="input-group-text" id="addon-wrapping"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control dt-search" placeholder="Search..." aria-label="Search" aria-describedby="addon-wrapping" aria-controls="dataTableBuilder">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                    <table id="datatable" class="table table-striped border">

                                    </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {

window.renderedDataTable = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        responsive: true,
        dom: '<"row align-items-center"><"table-responsive my-3" rt><"row align-items-center" <"col-md-6" l><"col-md-6" p>><"clear">',
        ajax: {
          "type"   : "GET",
          "url"    : '{{ route("postrequest.index_data",['id' => $id]) }}',
          "data"   : function( d ) {
            d.search = {
              value: $('.dt-search').val()
            };
            d.filter = {
              column_status: $('#column_status').val()
            }
          },
        },
        columns: [
            {
                name: 'DT_RowIndex',
                data: 'DT_RowIndex',
                title: "{{__('messages.no')}}",
                exportable: false,
                orderable: false,
                searchable: false,
            },
            {
                data: 'post_request_id',
                name: 'post_request_id',
                title: "{{ __('messages.postjob') }}"
            },
            {
                data: 'provider_id',
                name: 'provider_id',
                title: "{{ __('messages.provider') }}"
            },
            {
                data: 'customer_id',
                name: 'customer_id',
                title: "{{ __('messages.customer') }}"
            },
            {
                data: 'price',
                name: 'price',
                title: "{{ __('messages.price') }}"
            },
            {
                data: 'duration',
                name: 'duration',
                title: "{{ __('messages.duration') }}"
            }
            
        ]
        
    });
});
</script>
</x-master-layout>