<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PostJobRequest;
class CheckPostJobRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:postjobrequest';

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
        $post_request = PostJobRequest::where('status', 'requested')->get();
        foreach ($post_request as $key => $post) {
            $post_job_date = date("Y-m-d",strtotime($post->date));
            $today_date = date('Y-m-d');
            if(strtotime($post_job_date) < strtotime($today_date)){
                \Log::info('Cancel postjob : -  '.$post->id);

                $post->status = config('constant.POST_STATUS.CANCELLED');;
                $post->save();
            }
            \Log::info('Not found any post job');
        }
    }
}
