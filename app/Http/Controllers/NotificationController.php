<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    public function index()
    {

        $pageTitle = __('messages.list_form_title',['form' => __('messages.notification')] );
        $assets = ['datatable'];

        return view('notification.index', compact('pageTitle','assets'));
    }
    public function index_data(DataTables $datatable)
    {
        $row = \Auth::user()->notifications;
        
        return $datatable->collection($row)
        ->editColumn('type', function ($row) {
            return '<a class="btn-link btn-link-hover notify-table-link" href="'.route('booking.show',$row->data['id']) .'" ># '.$row->data['id'].' '.str_replace("_"," ",ucfirst($row->data['type'])).'</a>';
        })
        ->editColumn('message', function ($row) {
            return $row->data['message'];
        })
        ->editColumn('created_at', function ($row) {
            return dateAgoFormate($row->created_at,true);
        })

        ->setRowClass(function ($user) {
            return $user->read_at == null ? 'iq-bg-primary' : '';
        })

        ->editColumn('updated_at', function ($row) {
            return dateAgoFormate($row->updated_at,true);
        })
        ->editColumn('action', function ($row) {
            return '<a href="'.route('booking.show',$row->data['id']) .'"><span class="iq-bg-info mr-2"><i class="far fa-eye text-secondary"></i></span></a>';
        })
        ->addIndexColumn()
        ->rawColumns(['type','action','thread'])
        ->toJson();
    }
    public function notificationList(Request $request){
        $user = auth()->user();
        $user->last_notification_seen = now();
        $user->save();

        $type = isset($request->type) ? $request->type : null;
        if($type == "markas_read"){

            if(count($user->unreadNotifications) > 0 ) {
                $user->unreadNotifications->markAsRead();
            }
            $notifications = $user->notifications->take(5);
        } elseif($type == null) {
            $notifications = $user->notifications->take(5);
        } else {
            $notifications = $user->notifications->where('data.type',$type)->take(5);
        }
        $all_unread_count=isset($user->unreadNotifications) ? $user->unreadNotifications->count() : 0;

        $new_booking_count =  isset($user->unreadNotifications) ? $user->unreadNotifications->where('data.type','booking_added')->count() : 0;
        
        return response()->json([
            'status'     => true,
            'type'       => $type,
            'data'       => view('notification.list', compact('notifications','new_booking_count','all_unread_count','user'))->render()
        ]);
    }

    public function notificationCounts(Request $request)
    {

        $user = auth()->user();

        $unread_count = 0;
        $unread_total_count = 0;

        if(isset($user->unreadNotifications)){
            $unread_count =$user->unreadNotifications->where('created_at', '>', $user->last_notification_seen)->count() ;
            $unread_total_count = $user->unreadNotifications->count();
        }
        return response()->json([
            'status'     => true,
            'counts' => $unread_count,
            'unread_total_count' => $unread_total_count
        ]);
    }
}
