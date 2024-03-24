<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body">
                        <h5 class="card-title">{{__('messages.earning')}}</h5>
                            <div class="table-responsive">
                                <table class="table handydata-table mb-0">
                                    <thead class="table-color-heading">
                                        <tr class="text-secondary">
                                        <th scope="col">{{__('messages.handyman')}}</th>
                                        <th scope="col">{{__('messages.commission')}}</th>
                                        <th scope="col">{{__('messages.booking')}}</th>
                                        <th scope="col">{{__('messages.total_amount')}}</th>
                                        <th scope="col">{{__('messages.total_earning')}}</th>
                                        <th scope="col">{{__('messages.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('bottom_script')
<script type="text/javascript">
var table = $('.handydata-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('handymanEarningData') }}",
    columns: [
        {data: 'handyman_name', name: 'handyman_name'},
        {data: 'commission', name: 'commission'},
        {data: 'total_bookings', name: 'total_bookings'},
        {data: 'total', name: 'total'},
        {data: 'total_earning', name: 'total_earning'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
});
</script>
@endsection
</x-master-layout>