<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostJobBid;
use App\Models\PostJobRequest;
class PostJobBidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $customer = PostJobRequest::where('id',$data['post_request_id'])->first();
        $data['customer_id'] = $customer->customer_id;
        $result = PostJobBid::updateOrCreate(['id' => $request->id], $data);
        $activity_data = [
            'activity_type' => 'provider_send_bid',
            'bid_data' => $result,
        ];

        saveJobActivity($activity_data);
        $message = __('messages.update_form',[ 'form' => __('messages.postbid') ] );
		if($result->wasRecentlyCreated){
			$message = __('messages.save_form',[ 'form' => __('messages.postbid') ] );
		}

        if($request->is('api/*')) {
            return comman_message_response($message);
		}

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
