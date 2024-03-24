use Lavary\Menu\Menu;
@php
    $url = '';

    $MyNavBar = \Menu::make('MenuList', function ($menu) use($url){
        
        //Admin Dashboard
        $menu->add('<span>'.__('messages.dashboard').'</span>', ['route' => 'home'])
            ->prepend('<i class="ri-dashboard-line"></i>')
            
            ->link->attr(['class' => '']);

        $menu->add('<span>'.__('messages.category').'</span>', ['class' => ''])
            ->prepend('<i class="ri-shopping-basket-2-line"></i>')
            ->nickname('category')
            ->data('permission', 'category list')
            ->link->attr(["class" => ""])
            ->href('#categories');

            $menu->category->add('<span>'.__('messages.list_form_title',['form' => trans('messages.category') ]).'</span>', [ 'class' => 'sidebar-layout' , 'route' => ['category.index']])
                ->prepend('<i class="ri-list-unordered"></i>')
                ->data('permission', 'category list')
                ->link->attr(array('class' => ''));

            $menu->category->add('<span>'.__('messages.add_form_title',['form' => trans('messages.category')]).'</span>', array( 'class' => 'sidebar-layout', 'route' => 'category.create'))
                ->prepend('<i class="ri-add-box-line"></i>')
                ->data('permission', 'category add')
                ->link->attr(['class' => '']);

        $menu->add('<span>'.__('messages.document').'</span>', ['class' => ''])
            ->prepend('<i class="ri-shopping-basket-2-line"></i>')
            ->nickname('document')
            ->data('permission', 'document list')
            ->link->attr(["class" => ""])
            ->href('#document');

            $menu->document->add('<span>'.__('messages.list_form_title',['form' => trans('messages.document') ]).'</span>', [ 'class' => 'sidebar-layout' , 'route' => ['document.index']])
                ->prepend('<i class="ri-list-unordered"></i>')
                ->data('permission', 'document list')
                ->link->attr(array('class' => ''));

            $menu->document->add('<span>'.__('messages.add_form_title',['form' => trans('messages.document')]).'</span>', array( 'class' => 'sidebar-layout', 'route' => 'document.create'))
                ->prepend('<i class="ri-add-box-line"></i>')
                ->data('permission', 'document add')
                ->link->attr(['class' => '']);
            
        $menu->add('<span>'.trans('messages.service').'</span>', ['class' => ''])
                ->prepend('<i class="ri-service-line"></i>')
                ->nickname('service')
                ->data('permission', 'service list')
                ->link->attr(["class" => ""])
                ->href('#services');
    
                $menu->service->add('<span>'.trans('messages.list_form_title',['form' => trans('messages.service')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'service.index'])
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->data('permission', 'service list')
                    ->link->attr(['class' => '']);

                $menu->service->add('<span>'.trans('messages.add_form_title',['form' => trans('messages.service')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'service.create'])
                    ->data('permission', 'service add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);

                $menu->service->add('<span>'.trans('messages.list_form_title',['form' => trans('messages.rating')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'ratingreview.index'])
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->data('permission', 'service list')
                    ->link->attr(['class' => '']);

        $menu->add('<span>'.__('messages.provider').'</span>', ['class' => ''])
                ->prepend('<i class="la la-users"></i>')
                ->nickname('provider')
                ->data('permission', 'provider list')
                ->link->attr(["class" => ""])
                ->href('#providers');
    
                $menu->provider->add('<span>'.__('messages.list_form_title',['form' => __('messages.provider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'provider.index'])
                    ->data('permission', 'provider list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->provider->add('<span>'.__('messages.add_form_title',['form' => __('messages.provider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'provider.create'])
                    ->data('permission', 'provider add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);

                $menu->provider->add('<span>'.__('messages.pending_list_form_title',['form' => __('messages.provider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['provider.pending','pending']])
                    ->data('permission', 'pending provider') 
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->provider->add('<span>'.__('messages.payout_history').'</span>', ['class' => 'sidebar-layout' ,'route' => ['providerpayout.index']])
                    ->data('permission', 'provider payout') 
                    ->prepend('<i class="fas fa-exchange-alt"></i>')
                    ->link->attr(['class' => '']);


        $menu->add('<span>'.__('messages.provider_address').'</span>', ['class' => ''])
                ->prepend('<i class="ri-file-list-3-line"></i>')
                ->nickname('provideraddress')
                ->data('permission', 'provideraddress list')
                ->link->attr(["class" => ""])
                ->href('#provideraddress');
    
                $menu->provideraddress->add('<span>'.__('messages.list_form_title',['form' => __('messages.provider_address')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'provideraddress.index'])
                    ->data('permission', 'provideraddress list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->provideraddress->add('<span>'.__('messages.add_form_title',['form' => __('messages.provider_address')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'provideraddress.create'])
                    ->data('permission', 'provideraddress add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);
        
        $menu->add('<span>'.__('messages.providertype').'</span>', ['class' => ''])
                ->prepend('<i class="ri-file-list-3-line"></i>')
                ->nickname('providertype')
                ->data('permission', 'providertype list')
                ->link->attr(["class" => ""])
                ->href('#providertypes');
    
                $menu->providertype->add('<span>'.__('messages.list_form_title',['form' => __('messages.providertype')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'providertype.index'])
                    ->data('permission', 'providertype list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->providertype->add('<span>'.__('messages.add_form_title',['form' => __('messages.providertype')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'providertype.create'])
                    ->data('permission', 'providertype add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);

        $menu->add('<span>'.__('messages.providerdocument').'</span>', ['class' => ''])
                ->prepend('<i class="ri-file-list-3-line"></i>')
                ->nickname('providerdocument')
                ->data('permission', 'providerdocument list')
                ->link->attr(["class" => ""])
                ->href('#providerdocument');
    
                $menu->providerdocument->add('<span>'.__('messages.list_form_title',['form' => __('messages.providerdocument')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'providerdocument.index'])
                    ->data('permission', 'providerdocument list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->providerdocument->add('<span>'.__('messages.add_form_title',['form' => __('messages.providerdocument')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'providerdocument.create'])
                    ->data('permission', 'providerdocument add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);
    
        $menu->add('<span>'.__('messages.bookings').'</span>', ['route' => 'booking.index'])
                ->prepend('<i class="ri-list-unordered"></i>')
                ->nickname('booking')
                ->data('permission','booking list');

        $menu->add('<span>'.__('messages.tax').'</span>', ['route' => 'tax.index'])
                ->prepend('<i class="fas fa-percent"></i>')
                ->nickname('tax')
                ->data('role','admin');
                
        $menu->add('<span>'.__('messages.earning').'</span>', ['route' => 'earning'])
                ->prepend('<i class="fas fa-money-bill-alt"></i>')
                ->nickname('earning')
                ->data('role','admin');

               
        $menu->add('<span>'.__('messages.handyman').'</span>', ['class' => ''])
                ->prepend('<i class="las la-user-friends"></i>')
                ->nickname('handyman')
                ->data('permission', 'handyman list')
                ->link->attr(["class" => ""])
                ->href('#handymans');
    
                $menu->handyman->add('<span>'.__('messages.list_form_title',['form' => __('messages.handyman')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'handyman.index'])
                    ->data('permission', 'handyman list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->handyman->add('<span>'.__('messages.add_form_title',['form' => __('messages.handyman')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'handyman.create'])
                    ->data('permission', 'handyman add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);

                $menu->handyman->add('<span>'.__('messages.pending_list_form_title',['form' => __('messages.handyman')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['handyman.pending','pending']])
                    ->data('permission', 'pending handyman')    
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);
                
     
        $menu->add('<span>'.__('messages.users').'</span>', ['route' => 'user.index'])
                ->prepend('<i class="ri-list-unordered"></i>')
                ->nickname('user')
                ->data('permission', 'user list');

        $menu->add('<span>'.__('messages.coupon').'</span>', ['class' => ''])
                ->prepend('<i class="ri-coupon-fill"></i>')
                ->nickname('coupon')
                ->data('permission', 'coupon list')
                ->link->attr(["class" => ""])
                ->href('#coupons');
    
                $menu->coupon->add('<span>'.__('messages.list_form_title',['form' => __('messages.coupon')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'coupon.index'])
                    ->data('permission', 'coupon list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->coupon->add('<span>'.__('messages.add_form_title',['form' => __('messages.coupon')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'coupon.create'])
                    ->data('permission', 'coupon add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);
                    
        $menu->add('<span>'.trans('messages.payment').'</span>', ['class' => ''])
                ->prepend('<i class="ri-secure-payment-line"></i>')
                ->nickname('payment')
                ->data('permission', 'payment list')
                ->link->attr(["class" => ""])
                ->href('#payment');

                $menu->payment->add('<span>'.trans('messages.list_form_title',['form' => trans('messages.payment')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'payment.index'])
                    ->data('permission', 'payment list')
                    ->prepend('<i class="ri-list-unordered"></i>')  
                    ->link->attr(['class' => '']);
        
        $menu->add('<span>'.__('messages.slider').'</span>', ['class' => ''])
                ->prepend('<i class="ri-slideshow-line"></i>')
                ->nickname('sliders')
                ->data('permission', 'slider list')
                ->link->attr(["class" => ""])
                ->href('#sliders');
    
                $menu->sliders->add('<span>'.__('messages.list_form_title',['form' => __('messages.slider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'slider.index'])
                    ->data('permission', 'slider list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->sliders->add('<span>'.__('messages.add_form_title',['form' => __('messages.slider')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'slider.create'])
                    ->data('permission', 'slider add')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);
        
        $menu->add('<span>'.__('messages.account_setting').'</span>', ['class' => ''])
                ->prepend('<i class="ri-list-settings-line"></i>')
                ->nickname('account_setting')
                ->data('permission', ['role list','permission list'])
                ->link->attr(["class" => ""])
                ->href('#account_setting');

                $menu->account_setting->add('<span>'.__('messages.list_form_title',['form' => __('messages.role')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'role.index'])
                    ->data('permission', 'role list')
                    ->prepend('<i class="ri-list-unordered"></i>')
                    ->link->attr(['class' => '']);

                $menu->account_setting->add('<span>'.__('messages.list_form_title',['form' => __('messages.permission')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'permission.index'])
                    ->data('permission', 'permission list')
                    ->prepend('<i class="ri-add-box-line"></i>')
                    ->link->attr(['class' => '']);
        
        $menu->add('<span>'.__('messages.pages').'</span>', ['class' => ''])
                ->prepend('<i class="ri-pages-line"></i>')
                ->nickname('pages')
                ->data('permission', 'pages')
                ->link->attr(["class" => ""])
                ->href('#pages');
                $menu->pages->add('<span>'.__('messages.terms_condition').'</span>', ['class' => 'sidebar-layout' ,'route' => 'term-condition'])
                    ->data('permission', 'terms condition')
                    ->prepend('<i class="fas fa-file-contract"></i>')
                    ->link->attr(['class' => '']);
                
                $menu->pages->add('<span>'.__('messages.privacy_policy').'</span>', ['class' => 'sidebar-layout' ,'route' => 'privacy-policy'])
                    ->data('permission', 'privacy policy')
                    ->prepend('<i class="ri-file-shield-2-line"></i>')
                    ->link->attr(['class' => '']);

        $menu->add('<span>'.__('messages.setting').'</span>', ['route' => 'setting.index'])
                ->prepend('<i class="ri-settings-2-line"></i>')
                ->nickname('setting')
                ->data('permission', 'system setting');

        })->filter(function ($item) {
            return checkMenuRoleAndPermission($item);
        });

@endphp
<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-end justify-content-between">
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal light-logo site_logo_preview" alt="logo">
            <img src="{{ getSingleMedia(settingSession('get'),'site_logo',null) }}" class="img-fluid rounded-normal darkmode-logo site_logo_preview" alt="logo">
            <span class="white-space-no-wrap">{{ ucfirst(str_replace("_"," ",auth()->user()->user_type)) }}</span>
        </a>
        <div class="side-menu-bt-sidebar-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-light wrapper-menu" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
    <div class="data-scrollbar" data-scroll="1">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="side-menu">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
            </ul>
        </nav>
        <div class="pt-5 pb-5"></div>
    </div>
</div>