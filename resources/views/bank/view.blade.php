<x-master-layout>
    {{ Form::open(['route' => ['provider.destroy', $providerdata->id], 'method' => 'delete','data--submit'=>'provider'.$providerdata->id]) }}
    <main class="main-area">
        <div class="main-content">
            <div class="container-fluid">
                @include('partials._provider')
                <div class="card">
                    <div class="border-bottom d-flex gap-3 flex-wrap justify-content-between align-items-center px-4 py-3">
                        <div class="d-flex gap-2 align-items-center">
                            <i class="ri-bank-fill"></i>
                            <h4>{{ __('messages.bank_info') }}</h4>
                        </div>
                        <div class="d-flex gap-2 align-items-center"></div>
                    </div>

                    <div class="card-body p-30">
                      

                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-md-8 col-lg-6 col-xl-5">
                                <div class="card bank-info-card bg-bottom bg-contain bg-img" style="background-image: url('/img/bank-info-card-bg.png');">
                                    <div class="border-bottom p-3">
                                        <h4 class="fw-semibold">
                                             {{ __('messages.holder') }}: <strong>{{($providerdata->providerbank[0])->providers->display_name ?? '-'}}</strong> 

                                        </h4>
                                    </div>
                                    <div class="card-body position-relative">
                                        <img class="bank-card-img" src="/img/bank-card.png" alt="" />
                                        <ul class="bank-info-box list-unstyled d-flex flex-column gap-4">
                                            <li>
                                                <h3 class="mb-2">{{ __('messages.bank_name') }}:</h3>
                                                <div>{{( $providerdata->providerbank[0])->bank_name ?? '-'}}</div>
                                            </li>
                                            <li>
                                                <h3 class="mb-2">{{ __('messages.branch_name') }}:</h3>
                                                <div>{{( $providerdata->providerbank[0])->branch_name ?? '-'}}</div>
                                            </li>
                                            <li>
                                                <h3 class="mb-2">{{ __('messages.account_no') }}:</h3>
                                                <div>{{( $providerdata->providerbank[0])->account_no ?? '-'}}</div>
                                            </li>
                                        </ul>
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
   
</x-master-layout>