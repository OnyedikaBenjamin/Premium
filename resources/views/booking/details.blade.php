<x-master-layout>
    {{ Form::open(['route' => ['provider.destroy', $providerdata->id], 'method' => 'delete','data--submit'=>'provider'.$providerdata->id]) }}
    <main class="main-area">
        <div class="main-content">
            <div class="container-fluid">
                @include('partials._provider')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-block card-stretch">
                                    <div class="card-body">
                                        <h5 class="card-title">{{__('messages.booking')}}</h5>
                                        <div class="table-responsive">
                                            <table class="table data-table mb-0 provider-booking-data">
                                                <thead class="table-color-heading">
                                                    <tr class="text-secondary">
                                                        <th scope="col">{{__('messages.booking_id')}}</th>
                                                        <th scope="col">{{__('messages.provider_name')}}</th>
                                                        <th scope="col">{{__('messages.contact_number')}}</th>
                                                        <th scope="col">{{__('messages.amount')}}</th>
                                                        <th scope="col">{{__('messages.payment_status')}}</th>
                                                        <th scope="col">{{__('messages.start_at')}}</th>
                                                        <th scope="col">{{__('messages.end_at')}}</th>
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
        </div>
    </main>
    {{ Form::close() }}
    @section('bottom_script')
    <script type="text/javascript">
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'provider_name',
                    name: 'provider_name'
                },
                {
                    data: 'provider_contact',
                    name: 'provider_contact'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'payment_status',
                    name: 'payment_status'
                },
                {
                    data: 'start_at',
                    name: 'start_at'
                },
                {
                    data: 'end_at',
                    name: 'end_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    </script>
    @endsection
</x-master-layout>