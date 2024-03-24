<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Models\Setting;
use App\Models\User;
use App\Models\Country;
use App\Models\Service;
use Session;
use Config;
use Hash;
use Validator;
use App\Http\Requests\UserRequest;
use App\Notifications\CommonNotification;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function settings(Request $request)
    {
        $auth_user = authSession();

        $pageTitle = __('messages.setting');
        $page = $request->page;

        if ($page == '') {
            if ($auth_user->hasAnyRole(['admin', 'demo_admin'])) {
                $page = 'general-setting';
            } else {
                $page = 'profile_form';
            }
        }

        return view('setting.index', compact('page', 'pageTitle', 'auth_user'));
    }

    /*ajax show layout data*/
    public function layoutPage(Request $request)
    {
        $page = $request->page;
        $auth_user = authSession();
        $user_id = $auth_user->id;
        $settings = AppSetting::first();
        $user_data = User::find($user_id);
        $envSettting = $envSettting_value = [];
        if($auth_user['user_type'] == 'provider'){
            date_default_timezone_set($admin->time_zone ?? 'UTC');

            $current_time = \Carbon\Carbon::now();
            $time = $current_time->toTimeString();
    
            $current_day = strtolower(date('D'));
    
            $provider_id = $request->id ?? auth()->user()->id;
    
            $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    
            $slotsArray = ['days' => $days];
            $activeDay = 'mon';
            $activeSlots = []; 
    
            foreach ($days as $value) {
                $slot = ProviderSlotMapping::where('provider_id', $provider_id)
                ->where('days', $value)
                ->orderBy('start_at', 'DESC')
                ->selectRaw("SUBSTRING(start_at, 1, 5) as start_at")
                ->pluck('start_at')
                ->toArray();
            
                $obj = [
                    "day" => $value,
                    "slot" => $slot,
                ];
                $slotsArray[] = $obj;
                $activeSlots[$value] = $slot;
    
            }
            $pageTitle = __('messages.slot', ['form' => __('messages.slot')]);    
        }
        if (count($envSettting) > 0) {
            $envSettting_value = Setting::whereIn('key', array_keys($envSettting))->get();
        }
        if ($settings == null) {
            $settings = new AppSetting;
        } elseif ($user_data == null) {
            $user_data = new User;
        }
        switch ($page) {
            case 'time_slot':
                $data  = view('setting.' . $page, compact('settings', 'user_data', 'page','slotsArray', 'pageTitle', 'activeDay', 'provider_id', 'activeSlots'))->render();
                break;
            case 'password_form':
                $data  = view('setting.' . $page, compact('settings', 'user_data', 'page'))->render();
                break;
            case 'profile_form':
                $data  = view('setting.' . $page, compact('settings', 'user_data', 'page'))->render();
                break;
            case 'mail-setting':
                $data  = view('setting.' . $page, compact('settings', 'page'))->render();
                break;
            case 'config-setting':
                $setting = Config::get('mobile-config');
                $getSetting = [];
                foreach ($setting as $k => $s) {
                    foreach ($s as $sk => $ss) {
                        $getSetting[] = $k . '_' . $sk;
                    }
                }

                $setting_value = Setting::whereIn('key', $getSetting)->with('country')->get();

                $data  = view('setting.' . $page, compact('setting', 'setting_value', 'page'))->render();
                break;
            case 'payment-setting':
                $tabpage = 'cash';
                $data  = view('setting.' . $page, compact('settings', 'tabpage', 'page'))->render();
                break;
            case 'push-notification-setting':
                $settings = [];
                $services = Service::pluck('name', 'id');
                $data  = view('setting.' . $page, compact('settings', 'page', 'services'))->render();
                break;
            case 'advance-payment-setting':
                $payment   = Setting::where('type','=','ADVANCED_PAYMENT_SETTING')->first();
                $data  = view('setting.' . $page, compact('settings', 'page' ,'payment'))->render();
                break;
            case 'user-wallet-setting':
                $wallet = Setting::where('type', '=', 'USER_WALLET_SETTING')->first();
                $data = view('setting.' . $page, compact('settings', 'page', 'wallet'))->render();
                break;
            default:
                $data  = view('setting.' . $page, compact('settings', 'page', 'envSettting'))->render();
                break;
        }
        return response()->json($data);
    }

    public function configUpdate(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $auth_user = authSession();

        $data = $request->all();

        foreach ($data['key'] as $key => $val) {
            $value = ($data['value'][$key] != null) ? $data['value'][$key] : null;
            $input = [
                'type' => $data['type'][$key],
                'key' => $data['key'][$key],
                'value' => ($data['value'][$key] != null) ? $data['value'][$key] : null,
            ];
            Setting::updateOrCreate(['key' => $input['key']], $input);
            envChanges($data['key'][$key], $value);
        }
        return redirect()->route('setting.index', ['page' => 'config-setting'])->withSuccess(__('messages.updated'));
    }
    public function settingsUpdates(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $auth_user = authSession();

        $page = $request->page;
        $language_option = $request->language_option;
        if (!is_array($language_option)) {
            $language_option = (array)$language_option;
        }
        createLangFile($request->ENV['DEFAULT_LANGUAGE']);
        array_push($language_option, $request->ENV['DEFAULT_LANGUAGE']);

        $request->merge(['language_option' => $language_option]);

        $request->merge(['site_name' => str_replace("'", "", str_replace('"', '', $request->site_name))]);
        $request->merge(['time_zone' => $request->time_zone]);
        $res = AppSetting::updateOrCreate(['id' => $request->id], $request->all());
        $type = 'APP_NAME';
        $type = 'APP_TIMEZONE';
        $env = $request->ENV;

        $env['APP_NAME'] = $res->site_name;
        $env['APP_TIMEZONE'] = $res->time_zone;
        foreach ($env as $key => $value) {
            envChanges($key, $value);
        }

        $message = '';

        \App::setLocale($env['DEFAULT_LANGUAGE']);
        session()->put('locale', $env['DEFAULT_LANGUAGE']);

        storeMediaFile($res, $request->site_logo, 'site_logo');
        storeMediaFile($res, $request->site_favicon, 'site_favicon');

        settingSession('set');


        return redirect()->route('setting.index', ['page' => $page])->withSuccess(__('messages.updated'));
    }

    public function envChanges(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $auth_user = authSession();
        $page = $request->page;
        $env = $request->ENV;
        $envtype = $request->type;

        foreach ($env as $key => $value) {
            envChanges($key, str_replace('#', '', $value));
        }
        \Artisan::call('cache:clear');
        return redirect()->route('setting.index', ['page' => $page])->withSuccess(ucfirst($envtype) . ' ' . __('messages.updated'));
    }

    public function updateProfile(UserRequest $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $user = \Auth::user();
        $page = $request->page;

        $user->fill($request->all())->update();
        storeMediaFile($user, $request->profile_image, 'profile_image');

        return redirect()->route('setting.index', ['page' => 'profile_form'])->withSuccess(__('messages.profile') . ' ' . __('messages.updated'));
    }

    public function changePassword(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $user = User::where('id', \Auth::user()->id)->first();

        if ($user == "") {
            $message = __('messages.user_not_found');
            return comman_message_response($message, 400);
        }

        $validator = \Validator::make($request->all(), [
            'old' => 'required|min:8|max:255',
            'password' => 'required|min:8|confirmed|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('setting.index', ['page' => 'password_form'])->with('errors', $validator->errors());
        }

        $hashedPassword = $user->password;

        $match = Hash::check($request->old, $hashedPassword);

        $same_exits = Hash::check($request->password, $hashedPassword);
        if ($match) {
            if ($same_exits) {
                $message = __('messages.old_new_pass_same');
                return redirect()->route('setting.index', ['page' => 'password_form'])->with('error', $message);
            }

            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            \Auth::logout();
            $message = __('messages.password_change');
            return redirect()->route('setting.index', ['page' => 'password_form'])->withSuccess($message);
        } else {
            $message = __('messages.valid_password');
            return redirect()->route('setting.index', ['page' => 'password_form'])->with('error', $message);
        }
    }

    public function termAndCondition(Request $request)
    {
        $setting_data = Setting::where('type', 'terms_condition')->where('key', 'terms_condition')->first();
        $pageTitle = __('messages.terms_condition');
        $assets = ['textarea'];
        return view('setting.term_condition_form', compact('setting_data', 'pageTitle', 'assets'));
    }

    public function saveTermAndCondition(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $setting_data = [
            'type'  => 'terms_condition',
            'key'   =>  'terms_condition',
            'value' =>  $request->value
        ];
        $result = Setting::updateOrCreate(['id' => $request->id], $setting_data);
        if ($result->wasRecentlyCreated) {
            $message = __('messages.save_form', ['form' => __('messages.terms_condition')]);
        } else {
            $message = __('messages.update_form', ['form' => __('messages.terms_condition')]);
        }

        return redirect()->route('term-condition')->withsuccess($message);
    }

    public function privacyPolicy(Request $request)
    {
        $setting_data = Setting::where('type', 'privacy_policy')->where('key', 'privacy_policy')->first();
        $pageTitle = __('messages.privacy_policy');
        $assets = ['textarea'];

        return view('setting.privacy_policy_form', compact('setting_data', 'pageTitle', 'assets'));
    }

    public function savePrivacyPolicy(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $setting_data = [
            'type'   => 'privacy_policy',
            'key'   =>  'privacy_policy',
            'value' =>  $request->value
        ];
        $result = Setting::updateOrCreate(['id' => $request->id], $setting_data);
        if ($result->wasRecentlyCreated) {
            $message = __('messages.save_form', ['form' => __('messages.privacy_policy')]);
        } else {
            $message = __('messages.update_form', ['form' => __('messages.privacy_policy')]);
        }

        return redirect()->route('privacy-policy')->withsuccess($message);
    }

    public function helpAndSupport(Request $request)
    {
        $setting_data = Setting::where('type', 'help_support')->where('key', 'help_support')->first();
        $pageTitle = __('messages.help_support');
        $assets = ['textarea'];
        return view('setting.help_support_form', compact('setting_data', 'pageTitle', 'assets'));
    }

    public function saveHelpAndSupport(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $setting_data = [
            'type'  => 'help_support',
            'key'   =>  'help_support',
            'value' =>  $request->value
        ];
        $result = Setting::updateOrCreate(['id' => $request->id], $setting_data);
        if ($result->wasRecentlyCreated) {
            $message = __('messages.save_form', ['form' => __('messages.help_support')]);
        } else {
            $message = __('messages.update_form', ['form' => __('messages.help_support')]);
        }

        return redirect()->route('help-support')->withsuccess($message);
    }

    public function refundCancellationPolicy(Request $request)
    {
        $setting_data = Setting::where('type', 'refund_cancellation_policy')->where('key', 'refund_cancellation_policy')->first();
        $pageTitle = __('messages.refund_cancellation_policy');
        $assets = ['textarea'];
        return view('setting.refund_cancellation_policy_form', compact('setting_data', 'pageTitle', 'assets'));
    }

    public function saveRefundCancellationPolicy(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $setting_data = [
            'type'  => 'refund_cancellation_policy',
            'key'   =>  'refund_cancellation_policy',
            'value' =>  $request->value
        ];
        $result = Setting::updateOrCreate(['id' => $request->id], $setting_data);
        if ($result->wasRecentlyCreated) {
            $message = __('messages.save_form', ['form' => __('messages.refund_cancellation_policy')]);
        } else {
            $message = __('messages.update_form', ['form' => __('messages.refund_cancellation_policy')]);
        }

        return redirect()->route('refund-cancellation-policy')->withsuccess($message);
    }

    public function saveAppDownloadSetting(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $auth_user = authSession();

        $res = AppDownload::updateOrCreate(['id' => $request->id], $request->all());
        storeMediaFile($res, $request->app_image, 'app_image');
        return redirect()->route('setting.index', ['page' => 'config-setting'])->withSuccess(__('messages.updated'));
    }
    

    public function dashboardtogglesetting(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $value = json_encode($request->except('_token'));
        $data = [
            'type' => 'dashboard_setting',
            'key' => 'dashboard_setting',
            'value' => $value
        ];

        $res = Setting::updateOrCreate(['type' => 'dashboard_setting', 'key' => 'dashboard_setting'], $data);
        return redirect()->route('home');
    }
    public function providerdashboardtogglesetting(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $value = json_encode($request->except('_token'));
        $data = [
            'type' => 'provider_dashboard_setting',
            'key' => 'provider_dashboard_setting',
            'value' => $value
        ];

        $res = Setting::updateOrCreate(['type' => 'provider_dashboard_setting', 'key' => 'provider_dashboard_setting'], $data);
        return redirect()->route('home');
    }
    public function handymandashboardtogglesetting(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $value = json_encode($request->except('_token'));
        $data = [
            'type' => 'handyman_dashboard_setting',
            'key' => 'handyman_dashboard_setting',
            'value' => $value
        ];

        $res = Setting::updateOrCreate(['type' => 'handyman_dashboard_setting', 'key' => 'handyman_dashboard_setting'], $data);
        return redirect()->route('home');
    }
    public function sendPushNotification(Request $request)
    {
        $data = $request->all();
       
        if ($data['type'] === 'alldata') {
            $data['service_id'] = 0;
        }
        if($data['is_type'] == 'provider'){
            $data['type'] = 0;
            $data['service_id'] = 0;
        }
        $heading      = array(
            "en" => $data['title']
        );
        $content      = array(
            "en" => $data['description']
        );
        if($data['is_type'] == 'provider'){
          
            $fields = array(
                'app_id' => ENV('ONESIGNAL_APP_ID_PROVIDER'),
                'included_segments' => array(
                    'ProviderApp'
                ),
                'data' =>  array(
                    'type' => $data['type'],
                    'service_id' => $data['service_id']
                ),
                'headings' => $heading,
                'contents' => $content,
            );
            $fields = json_encode($fields);
            $rest_api_key = ENV('ONESIGNAL_REST_API_KEY_PROVIDER');
        }
        else{
            $fields = array(
                'app_id' => ENV('ONESIGNAL_API_KEY'),
                'included_segments' => array(
                    'UserApp'
                ),
                'data' =>  array(
                    'type' => $data['type'],
                    'service_id' => $data['service_id']
                ),
                'headings' => $heading,
                'contents' => $content,
            );
            $fields = json_encode($fields);
            $rest_api_key = ENV('ONESIGNAL_REST_API_KEY');
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            "Authorization:Basic $rest_api_key"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            $message = trans('messages.update_form', ['form' => trans('messages.pushnotification_settings')]);
        } else {
            $message = trans('messages.failed');
        }
        if (request()->is('api/*')) {
            return comman_message_response($message);
        }
        return redirect()->route('setting.index')->withSuccess($message);
    }
    public function saveEarningTypeSetting(Request $request)
    {
        if (demoUserPermission()) {
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $message = trans('messages.failed');
        $res = AppSetting::updateOrCreate(['id' => $request->id], $request->all());
        if ($res) {
            $message = trans('messages.update_form', ['form' => trans('messages.pushnotification_settings')]);
        }
        return redirect()->route('setting.index')->withSuccess($message);
      }
      public function comission($id)
    {
        $auth_user = authSession();
        $providerdata = User::with('providertype')->where('user_type', 'provider')->where('id',$id)->first();
        if (empty($providerdata)) {
            $msg = __('messages.not_found_entry', ['name' => __('messages.provider')]);
            return redirect(route('provider.index'))->withError($msg);
        }
        $pageTitle = __('messages.view_form_title', ['form' => __('messages.provider')]);
        return view('setting.comission', compact('pageTitle', 'providerdata', 'auth_user'));
    }

    /* advance earnning setting */ 
    public function advanceEarningSetting(Request $request)
    {
            $data = $request->all();
            $value = json_encode($request->except('_token'));
            $message = trans('messages.failed');
            if($request->value == 'on'){
                $data['value'] = 1;
            }
            else{
                $data['value'] = 0;
            }
            $data = [ 
                'type' => 'ADVANCED_PAYMENT_SETTING',
                'key'  => 'ADVANCED_PAYMENT',
                'value' =>$data['value'],
            ];
            $res = Setting::updateOrCreate(['id' => $request->id],$data);
            if ($res) {
                $message = trans('messages.update_form', ['form' => trans('messages.enable_payment')]);
            }
            return redirect()->route('setting.index')->withSuccess($message);
    }

    public function enableUserWallet(Request $request){

        $data = $request->all();
        $value = json_encode($request->except('_token'));
        $message = trans('messages.failed');
        
        if($request->value == 'on'){
            $data['value'] = 1;
        }
        else{
            $data['value'] = 0;
        }
        $data = [ 

            'type' => 'USER_WALLET_SETTING',
            'key'  => 'ENABLE_USER_WALLET',
            'value' =>$data['value'],
        ];
        $res = Setting::updateOrCreate(['id' => $request->id],$data);
        if ($res) {
            $message = trans('messages.update_form', ['form' => trans('messages.enable_user_wallet')]);
        }
        return redirect()->route('setting.index')->withSuccess($message);

    }
}
