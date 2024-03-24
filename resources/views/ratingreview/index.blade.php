<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <h5 class="font-weight-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                        </div>
                        <div class="col-lg-3 justify-content-end align-items-center">
                            <div class="input-group ">
                                <span class="input-group-text" id="addon-wrapping"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control dt-search" placeholder="Search..." aria-label="Search" aria-describedby="addon-wrapping" aria-controls="dataTableBuilder">
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
                  "url"    : '{{ route("ratingreview.index_data") }}',
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
                        title: "{{__('messages.srno')}}",
                        exportable: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'service_id',
                        name: 'service_id',
                        title: "{{ __('messages.service') }}"
                    },
                    {
                        data: 'customer_id',
                        name: 'customer_id',
                        title: "{{ __('messages.customer') }}"
                    },
                    {
                        data: 'rating',
                        name: 'rating',
                        title: "{{ __('messages.rating') }}"
                    },
                    {
                        data: 'review',
                        name: 'review',
                        title: "{{ __('messages.review') }}"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: "{{ __('messages.action') }}"
                    }
                    
                ]
                
            });
      });
</script>
</x-master-layout>