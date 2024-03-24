<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderSlotMapping;
class ProviderSlotController extends Controller
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
        $slotdata = $request->all();
        $provider_id = !empty($request->provider_id) ? $request->provider_id :\Auth::user()->id;

        $provider_slot = ProviderSlotMapping::where('provider_id',$provider_id)->get();
        if(count($provider_slot) > 0){
            $provider_slot->each->delete();
        }
        if(!empty($slotdata['slots'])){
            foreach ($slotdata['slots'] as $key => $value) {
                if(!empty($value['time'])){
                    foreach ($value['time'] as $t => $time) {
                        $slotArray = [
                            'provider_id' => $provider_id,
                            'days' => $value['day'],
                            'start_at' => $time,
                            'end_at' => date('H:i',strtotime('+1 hour',strtotime($time)))
                        ];
                        $res = ProviderSlotMapping::create($slotArray);
                        $message = __('messages.update_form',[ 'form' => __('messages.providerslot') ] );
                        if($res->wasRecentlyCreated){
                            $message = __('messages.save_form',[ 'form' => __('messages.providerslot') ] );
                        }
                
                    }
                }
                $message = __('messages.update_form',[ 'form' => __('messages.providerslot') ] );
               
            }
        }
       
        return comman_message_response($message);
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
