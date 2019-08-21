<?php

namespace App\Http\Controllers\Api;

use App\AppDefault;
use App\Category;
use App\Http\Controllers\Controller;
use App\Service;
use Illuminate\Http\Request;

class CoreController extends Controller
{
    public function services()
    {
        $services = Service::all();
        return response()->json($services);
    }

    public function items()
    {
        $items = Category::select('id','name')->with('items:id,category_id,name')->get();
        return response()->json($items);
    }
    public function getSettings($settingType)
    {
        if($settingType!=''){
            $settings = config('settings.'.$settingType);
            return response()->json($settings);
        }
        else{
            return response()->json([
                'status' => 403,
                'message'=> 'Setting type required'
            ],403);
        }
    }
    public function supportInfo()
    {
        $appDefaults = AppDefault::first();
        $appDefaults['company_logo'] = asset('files/'.$appDefaults->company_logo);
        $appDefaults['online_chat'] = json_decode($appDefaults->online_chat);

        $input = $appDefaults->only('company_logo', 'company_email', 'hotline_contact', 'FAQ_link', 'online_chat');

        return response()->json($input);
    }
}
