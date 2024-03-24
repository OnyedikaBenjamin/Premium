<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use App\Models\Coupon;
use App\Models\AppDownload;


use App\Http\Resources\API\CategoryResource;
use App\Http\Resources\API\ServiceResource;
use App\Http\Resources\API\UserResource;
use App\Http\Resources\API\ServiceDetailResource;
use App\Http\Resources\API\BookingRatingResource;
use App\Http\Resources\API\CouponResource;

class FrontendController extends Controller
{
    public function index(){
        $locale = app()->getLocale();
        return view('frontend.index',compact('locale'));
    }
    public function appDownloadPage(Request $request)
    {
        $appdata = AppDownload::first();
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.app_download')]);
        
        if($appdata == null){
            $pageTitle = trans('messages.add_button_form',['form' => trans('messages.app_download')]);
            $appdata = new AppDownload;
        }

        return view('frontend.app-settings',compact('pageTitle','appdata'));
    }
    public function saveAppDownloadPage(Request $request){
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $auth_user = authSession();
        $res = AppDownload::updateOrCreate(['id' => $request->id], $request->all());
        storeMediaFile($res,$request->app_image, 'app_image');
        storeMediaFile($res,$request->app_image_full, 'app_image_full');
        return redirect()->route('app-download')->withSuccess( __('messages.updated'));
    }
}
