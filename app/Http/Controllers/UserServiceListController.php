<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\User;
use Yajra\DataTables\DataTables;
use App\Models\PackageServiceMapping;

class UserServiceListController extends Controller
{
   
    public function index_data(DataTables $datatable,Request $request)
    {
       
        $query = Service::query();
        

        $filter = $request->filter;

        if (isset($filter)) {
            if (isset($filter['column_status'])) {
                $query->where('status', $filter['column_status']);
            }
        }
        if(auth()->user()->hasAnyRole(['admin'])){
            $query = $query->where('service_type','user_post_service')->withTrashed();
        }
        
        return $datatable->eloquent($query)
            ->addColumn('check', function ($row) {

                return '<input type="checkbox" class="form-check-input select-table-row"  id="datatable-row-'.$row->id.'"  name="datatable_ids[]" value="'.$row->id.'" onclick="dataTableRowCheck('.$row->id.')">';
            })
            ->editColumn('category_id' , function ($service){
                return ($service->category_id != null && isset($service->category)) ? $service->category->name : '-';
            })
            ->filterColumn('category_id',function($query,$keyword){
                $query->whereHas('category',function ($q) use($keyword){
                    $q->where('name','like','%'.$keyword.'%');
                });
            })
             // ->editColumn('customer_id', function ($service) {
            //     $services = Service::with('serviceBooking.customer')->get();

            //     if ($service->serviceBooking()->exists()) {
            //         return $service->serviceBooking->first()->customer->display_name;
            //     } else {
            //         return '';
            //     }
                
            // })
            ->editColumn('customer_id', function ($service) {
                $services = Service::with('serviceBooking.customer')->get();

                if ($service->serviceBooking()->exists()) {
                    return view('service.userservice', compact('service'));
                } else {
                    return '-';
                }
                
            })
            ->filterColumn('customer_id',function($query,$keyword){
                $query->whereHas('serviceBooking.customer',function ($q) use($keyword){
                    $q->where('display_name','like','%'.$keyword.'%');
                });
            })
            ->editColumn('price' , function ($service){
                return getPriceFormat($service->price).'-'.ucFirst($service->type);
            })
            
            ->editColumn('discount' , function ($service){
                return $service->discount ? $service->discount .'%' : '-';
            })
            ->addColumn('action', function ($data) {
                return view('service.action', compact('data'));
            })
            ->editColumn('status' , function ($query){
                $disabled = $query->trashed() ? 'disabled': '';
                return '<div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                    <div class="custom-switch-inner">
                        <input type="checkbox" class="custom-control-input  change_status" data-type="subcategory_status" '.($query->status ? "checked" : "").'  '.$disabled.' value="'.$query->id.'" id="'.$query->id.'" data-id="'.$query->id.'">
                        <label class="custom-control-label" for="'.$query->id.'" data-on-label="" data-off-label=""></label>
                    </div>
                </div>';
            })
            ->rawColumns(['check','action','status','is_featured'])
            ->toJson();
    }   
}
