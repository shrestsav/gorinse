<?php

namespace App\Http\Controllers\Api;

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
}
