<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs pay-tabs nav-fill tabslink" id="tab-text" role="tablist">
            <li class="nav-item payment-link1">
                <a href="javascript:void(0)" data-href="{{ route('razorpay_layout_page') }}?tabpage=razorPay" data-target=".rezorpay_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='razorPay'?'active':''}}"   rel="tooltip"> {{ __('messages.razor') }}</a>
            </li>
            <li class="nav-item payment-link1">
                <a href="javascript:void(0)" data-href="{{ route('razorpay_layout_page') }}?tabpage=razorPayX" data-target=".rezorpay_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage==''?'active':''}}"   rel="tooltip"> {{__('messages.razorx')}}</a>
            </li>
           
        </ul>
        <div class="card payment-content-wrapper">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent-1">
                    <div class="tab-pane active p-1" >
                        <div class="rezorpay_paste_here">
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
<script>
    
    $(document).ready(function(event)
    {
       
        var $this = $('.payment-link1').find('a.active');
        loadurl = '{{route('razorpay_layout_page')}}?tabpage={{$tabpage}}';
   
        id = this.id || '';

        targ = $this.attr('data-target');

        $.post(loadurl,{ '_token': $('meta[name=csrf-token]').attr('content') } ,function(data) {
            $(targ).html(data);
        });

        $this.tab('show');
        return false;
    });
</script>