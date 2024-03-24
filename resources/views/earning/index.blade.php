<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body">
                        <h5 class="card-title">{{__('messages.earning')}}</h5>
                            
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
                  "url"    : '{{ route("earningData") }}',
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
                        data: 'provider_name',
                        name: 'provider_name',
                        title: "{{__('messages.provider')}}"
                    },
                    {
                        data: 'commission',
                        name: 'commission',
                        title: "{{__('messages.commission')}}"
                    },
                    {
                        data: 'total_bookings',
                        name: 'total_bookings',
                        title: "{{__('messages.booking')}}"
                    },
                    {
                        data: 'total_earning',
                        name: 'total_earning',
                        title: "{{__('messages.total_earning')}}"
                    },
                    {
                        data: 'taxes',
                        name: 'taxes',
                        title: "{{__('messages.tax')}}"
                    },
                    {
                        data: 'admin_earning',
                        name: 'admin_earning',
                        title: "{{__('messages.admin_earning')}}"
                    },
                    {
                        data: 'provider_earning',
                        name: 'provider_earning',
                        title: "{{__('messages.provider_earning')}}"
                    },
                    
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        title: "{{__('messages.action')}}"
                    }
                    
                ]
                
            });
      });


    $(document).on('click', '[data-ajax="true"]', function (e) {
      e.preventDefault();
      const button = $(this);
      const confirmation = button.data('confirmation');

      if (confirmation === 'true') {
          const message = button.data('message');
          if (confirm(message)) {
              const submitUrl = button.data('submit');
              const form = button.closest('form');
              form.attr('action', submitUrl);
              form.submit();
          }
      } else {
          const submitUrl = button.data('submit');
          const form = button.closest('form');
          form.attr('action', submitUrl);
          form.submit();
      }
  });

    </script>
</x-master-layout>