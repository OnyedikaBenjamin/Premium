{{ Form::hidden('id', null, array('placeholder' => 'id','class' => 'form-control')) }}
{{ Form::hidden('type', 'hjg', array('placeholder' => 'id','class' => 'form-control')) }}
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body">
                            <h5 class="card-title">{{__('messages.plan')}}</h5>
                            <div class="table-responsive">
                                <table class="table data-table mb-0">
                                    <thead class="table-color-heading">
                                        <tr class="text-secondary">
                                            <th scope="col">{{__('messages.planname')}}</th>
                                            <th scope="col">{{__('messages.planType')}}</th>
                                            <th scope="col">{{__('messages.plan_type')}}</th>
                                            
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
{{ Form::close() }}
<script type="text/javascript">

var loadurl = '{{route('provider_detail_pages')}}?tabpage=subscribe-plan&type=tbl&providerid={{ request()->providerid }}';

    var table = $('.data-table').DataTable({
        processing: true,
          serverSide: true,

        ajax: {
            url: loadurl,
            type: 'GET'
        },
        columns: [
            {
                data: 'title',
                name: 'title'

            },
            {
                data: 'type',
                name: 'type'
            },
            
            {
                data: 'plan_type',
                name: 'plan_type'
            },
        ]
    });
</script>