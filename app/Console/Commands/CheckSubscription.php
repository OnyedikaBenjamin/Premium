<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ProviderSubscription;
class CheckSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $activeUser = User::where('is_subscribe', 1)->with('subscriptionPackage')->get();
        foreach ($activeUser as $key => $user) {
            $package = ProviderSubscription::where('user_id',$user->id)->where('status',config('constant.SUBSCRIPTION_STATUS.ACTIVE'))->first();
            $expired_date = date("Y-m-d",strtotime(optional($user->subscriptionPackage)->end_at));
            $today_date = date('Y-m-d');
            if(strtotime($expired_date) < strtotime($today_date)){
                \Log::info('Cancel Subscription : -  '.$user->id);

                $user->is_subscribe = 0;
                $user->save();

                $package->status = config('constant.SUBSCRIPTION_STATUS.INACTIVE');
                $package->save();
            }
            \Log::info('Not found any active user');
        }
    }
}
