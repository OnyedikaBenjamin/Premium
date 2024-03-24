<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProviderSubscription;
use App\Models\User;
use App\Models\SubscriptionTransaction;
use App\Http\Resources\API\ProviderSubscribeResource;
use App\Http\Requests\ProviderSubscriptionRequest; 

class SubscriptionController extends Controller
{
    public function providerSubscribe(ProviderSubscriptionRequest $request){

        $user_id = $request->user_id ? $request->user_id :auth()->id();

        $user = User::where('id',$user_id)->first();

        date_default_timezone_set( getTimeZone());

        $data = $request->all();

        $get_existing_plan = get_user_active_plan($user_id);

        $active_plan_left_days = 0;

        $data['user_id'] = $user_id;

        $data['status'] = config('constant.SUBSCRIPTION_STATUS.PENDING');


        $data['start_at'] = date('Y-m-d H:i:s');

        if($get_existing_plan){
            $active_plan_left_days  = check_days_left_plan($get_existing_plan,$data);
            if($request->identifier  != $get_existing_plan->identifier){
                $get_existing_plan->update([
                    'status' => config('constant.SUBSCRIPTION_STATUS.INACTIVE')
                ]);
                $get_existing_plan->save();
            }
        }

        $data['end_at'] = get_plan_expiration_date( $data['start_at'],$data['type'],$active_plan_left_days,$data['duration']);
        if(isset($data['plan_limitation']) && !empty($data['plan_limitation'] )){
            $data['plan_limitation'] = json_encode($data['plan_limitation']);
        }
        $result = ProviderSubscription::create($data);

        if( $result ){
            $payment_data =[
                'subscription_plan_id' => $result->id,
                'user_id' => $result->user_id,
                'amount' => $result->amount,
                'payment_status' => $request->payment_status,
                'payment_type' => $request->payment_type
            ];
           $payment = SubscriptionTransaction::create($payment_data);

           if($payment->payment_status == 'paid'){
                $result->status = config('constant.SUBSCRIPTION_STATUS.ACTIVE');
                $result->payment_id = $payment->id;
                $result->save();
                $user->is_subscribe = 1;
                $user->save();
                $message = __('messages.payment_completed');
           }
        }
        
        $items = new ProviderSubscribeResource($result);
        
        $response = [
            'data' => $items,
        ];

        return comman_custom_response($response);
    }

    public function cancelSubscription(Request $request){
        $user_id = $request->user_id ? $request->user_id : auth()->id();
        $plan_id  = $request->id;
        $provider_subscription = ProviderSubscription::where('id', $plan_id )->where('user_id',$user_id)->first();
        $user = User::where('id', $user_id)->first();
        if($provider_subscription){
            $provider_subscription->status =  config('constant.SUBSCRIPTION_STATUS.INACTIVE');
            $provider_subscription->save();
            $user->is_subscribe = 0;
            $user->save();
            $message = __('messages.cancelled_plan',['plan'=> $provider_subscription->title]);
        }
        return comman_message_response($message);
    }

    public function getHistory(Request $request){
        $user_id = auth()->id();
        $subscription_history = ProviderSubscription::where('user_id',$user_id);
        $per_page = config('constant.PER_PAGE_LIMIT');

        $orderBy = $request->orderby ? $request->orderby: 'asc';

        if( $request->has('per_page') && !empty($request->per_page)){
            if(is_numeric($request->per_page)){
                $per_page = $request->per_page;
            }
            if($request->per_page === 'all' ){
                $per_page = $subscription_history->count();
            }
        }

        $subscription_history = $subscription_history->orderBy('id',$orderBy)->paginate($per_page);
        $items = ProviderSubscribeResource::collection($subscription_history);

        $response = [
            'pagination' => [
                'total_items' => $items->total(),
                'per_page' => $items->perPage(),
                'currentPage' => $items->currentPage(),
                'totalPages' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
                'next_page' => $items->nextPageUrl(),
                'previous_page' => $items->previousPageUrl(),
            ],
            'data' => $items,
        ];

        return comman_custom_response($response);

    }
}
