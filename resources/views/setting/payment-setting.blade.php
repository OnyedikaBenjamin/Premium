<div class="row">
    <div class="col-lg-12">
        <ul class="nav nav-tabs pay-tabs nav-fill tabslink" id="tab-text" role="tablist">
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=cash" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='cash'?'active':''}}"   rel="tooltip"> {{ __('messages.cod') }}</a>
            </li>
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=stripe" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='stripe'?'active':''}}"   rel="tooltip"> {{__('messages.stripe')}}</a>
            </li>
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=razorPay" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='razorPay'?'active':''}}"   rel="tooltip"> {{__('messages.razor')}}</a>
            </li>
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=flutterwave" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='flutterwave'?'active':''}}"   rel="tooltip"> {{__('messages.flutterwave')}}</a>
            </li>
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=paypal" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='paypal'?'active':''}}"   rel="tooltip"> {{__('messages.paypal')}}</a>
            </li>
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=cinet" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='cinet'?'active':''}}"   rel="tooltip"> {{__('messages.cinet')}}</a>
            </li>
            <li class="nav-item payment-link">
                <a href="javascript:void(0)" data-href="{{ route('payment_layout_page') }}?tabpage=sadad" data-target=".payment_paste_here" data-toggle="tabajax"  class="nav-link  {{$tabpage=='sadad'?'active':''}}"   rel="tooltip"> {{__('messages.sadad')}}</a>
            </li>
        </ul>
        <div class="card payment-content-wrapper">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent-1">
                    <div class="tab-pane active p-1" >
                        <div class="payment_paste_here"></div>


                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
<script>
    $(document).ready(function(event)
    {
        var $this = $('.payment-link').find('a.active');
        loadurl = '{{route('payment_layout_page')}}?tabpage={{$tabpage}}';

        targ = $this.attr('data-target');
        
        id = this.id || '';

        $.post(loadurl,{ '_token': $('meta[name=csrf-token]').attr('content') } ,function(data) {
            $(targ).html(data);
        });

        $this.tab('show');
        return false;
    });
</script>