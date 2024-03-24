<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function paymentPage(Request $request){
        $tabpage = $request->tabpage;
        $auth_user = authSession();
        $user_id = $auth_user->id;
        $user_data = User::find($user_id);
        $payment_data = PaymentGateway::where('type',$tabpage)->first();

        switch ($tabpage) {
            case 'cash':
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;

            case 'stripe':
                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);
                    $payment_data['stripe_url'] = $decodedata->stripe_url;
                    $payment_data['stripe_key'] = $decodedata->stripe_key;
                    $payment_data['stripe_publickey'] = $decodedata->stripe_publickey;
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;

            case 'razorPay':

                // if(!empty($payment_data['value'])){
                //     $decodedata = json_decode($payment_data['value']);

                //     $payment_data['razor_url'] = $decodedata->razor_url;
                //     $payment_data['razor_key'] = $decodedata->razor_key;
                //     $payment_data['razor_secret'] = $decodedata->razor_secret;
                // }
                // $data  = view('paymentgateway.rezorpay_option', compact('user_data','tabpage','payment_data'))->render();
                $tabpage = 'razorPay';
                $data  = view('paymentgateway.rezorpay_option', compact('user_data', 'tabpage', 'payment_data'))->render();
                break;

            case 'flutterwave':
                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);
                    $payment_data['flutterwave_public'] = $decodedata->flutterwave_public;
                    $payment_data['flutterwave_secret'] = $decodedata->flutterwave_secret;
                    $payment_data['flutterwave_encryption'] = $decodedata->flutterwave_encryption;
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;

            case 'paystack':
                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);
                    $payment_data['paystack_public'] = $decodedata->paystack_public;
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;

            case 'paypal':
                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);

                    $payment_data['paypal_client_id'] = isset($decodedata->paypal_client_id) ? $decodedata->paypal_client_id : null;
                    $payment_data['paypal_secret_key'] = isset($decodedata->paypal_secret_key) ? $decodedata->paypal_secret_key : null;
                    
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;
    
            case 'cinet':
                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);

                    $payment_data['cinet_id'] = $decodedata->cinet_id;
                    $payment_data['cinet_key'] = $decodedata->cinet_key;
                    $payment_data['cinet_publickey'] = $decodedata->cinet_publickey;
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;
    
            case 'sadad':
                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);
                    $payment_data['sadad_id'] = $decodedata->sadad_id;
                    $payment_data['sadad_key'] = $decodedata->sadad_key;
                    $payment_data['sadad_domain'] = $decodedata->sadad_domain;
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;
            default:
                $data  = view('paymentgateway.'.$tabpage,compact('tabpage','payment_data'))->render();
                break;
        }
        return response()->json($data);
    }

     public function rezorpaypaymentPage(Request $request)
    {
       $tabpage = $request->tabpage;

        $auth_user = authSession();
        $user_id = $auth_user->id;
        $user_data = User::find($user_id);
        $payment_data = PaymentGateway::where('type',$tabpage)->first();

        switch($tabpage) {
    
            case 'razorPay':

                if(!empty($payment_data['value'])){
                    $decodedata = json_decode($payment_data['value']);

                    $payment_data['razor_url'] = $decodedata->razor_url;
                    $payment_data['razor_key'] = $decodedata->razor_key;
                    $payment_data['razor_secret'] = $decodedata->razor_secret;
                }
                $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                break;

                case 'razorPayX':

                    if(!empty($payment_data['value'])){
                        $decodedata = json_decode($payment_data['value']);
    
                        $payment_data['razorx_url'] = $decodedata->razorx_url;
                        $payment_data['razorx_account'] = $decodedata->razorx_account;
                        $payment_data['razorx_key'] = $decodedata->razorx_key;
                        $payment_data['razorx_secret'] = $decodedata->razorx_secret;
                    }
                    $data  = view('paymentgateway.'.$tabpage, compact('user_data','tabpage','payment_data'))->render();
                    break;
            
          
            default:
              
                $data  = view('paymentgateway.'.$tabpage,compact('tabpage','payment_data'))->render();
                break;
        }
        return response()->json($data);
       
    }

    public function paymentsettingsUpdates(Request $request){
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }

        $data = $request->all();
        $page = $request->page;
        $type = $request->type;


        $data['is_test'] = 0;
        $data['status'] = 0;

        if(isset($request->is_test) && $request->is_test == 'on'){
            $data['is_test'] = 1;
        }
        if($request->status == 'on'){
            $data['status'] = 1;
        }
       
        switch ($type) {
            case 'stripe':
                $config_data = [
                    'stripe_url' => $data['stripe_url'],
                    'stripe_key' => $data['stripe_key'],
                    'stripe_publickey' => $data['stripe_publickey']
                ];
                break;

            case 'razorPay':
                $config_data = [
                    'razor_url' => $data['razor_url'],
                    'razor_key' => $data['razor_key'],
                    'razor_secret' => $data['razor_secret']
                ];
                break;

                case 'razorPayX':
                    $config_data = [
                        'razorx_url' => $data['razorx_url'],
                        'razorx_account' => $data['razorx_account'],
                        'razorx_key' => $data['razorx_key'],
                        'razorx_secret' => $data['razorx_secret']
                    ];
               break;

            case 'flutterwave':
                $config_data = [
                    'flutterwave_public' => $data['flutterwave_public'],
                    'flutterwave_secret' => $data['flutterwave_secret'],
                    'flutterwave_encryption' => $data['flutterwave_encryption']
                ];
                break;

            case 'paystack':
                $config_data = [
                    'paystack_public' => $data['paystack_public'],
                ];
                break;

            case 'paypal':
                $config_data = [
                    'paypal_client_id' => $data['paypal_client_id'],
                    'paypal_secret_key' => $data['paypal_secret_key'],
                ];
                break;

            case 'cinet':
                $config_data = [
                    'cinet_id' => $data['cinet_id'],
                    'cinet_key' => $data['cinet_key'],
                    'cinet_publickey' => $data['cinet_publickey']
                ];
                break;

            case 'sadad':
                $config_data = [
                    'sadad_id' => $data['sadad_id'],
                    'sadad_key' => $data['sadad_key'],
                    'sadad_domain' => $data['sadad_domain']
                ];
                break;
            default:
                $config_data = [];
                break;
        }
        if(isset($request->is_test) && $request->is_test == 'on'){
            $data['value'] =  json_encode($config_data);
        }
        if(isset($request->is_test) && $request->is_test == 'off'){
            $data['live_value'] =  json_encode($config_data);
        }
        $res = PaymentGateway::updateOrCreate(['id' => $request->id], $data);
        return redirect()->route('setting.index', ['page' => $page])->withSuccess( __('messages.updated'));
    }

    public function getPaymentConfig(Request $request){
        $mode = $request->type;
        $page = $request->page;
        $select = 'value' ;

        if($mode == 'is_live_mode'){
            $select = 'live_value';
        }
        $payment_data = PaymentGateway::select('id','title', $select,'is_test','status','type')->where('type',$request->page)->first();
        $payment_data['type'] = $mode;

    
        return response()->json(['success'=>'Ajax request submitted successfully','data'=>$payment_data]);
    }
}
