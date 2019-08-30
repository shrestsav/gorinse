<?php

namespace App\Http\Controllers\Api;

use App\AppDefault;
use App\Category;
use App\Http\Controllers\Controller;
use App\MainArea;
use App\Offer;
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
        $items = Category::select('id','name')->with('items:id,category_id,name,icon')->get();
        return response()->json($items);
    }    

    public function servicesPlusItems()
    {
        $services = Service::all();
        $categoryWithItems = Category::select('id','name')->with('items')->get();
        
        $newCollection = [];
        foreach ($services as $service) {
            $serviceCharge = $service->price;
            $data = [
                'id'    =>  $service->id,
                'name'  =>  $service->name,
                'categories'  =>  [],
            ];
            foreach($categoryWithItems as $category){
                $newCategory = [];
                $newCategory = [
                    'id'    =>  $category->id,
                    'name'  =>  $category->name,
                    'items' =>  [],
                ];
                foreach ($category->items as $item) {
                    $newItem = [];
                    $newItem = [
                        'id'    =>  $item->id,
                        'name'  =>  $item->name,
                        'price'  =>  $item->price+$serviceCharge,
                        'icon'  =>  $item->icon,
                    ];
                    array_push($newCategory['items'],$newItem);
                }
                array_push($data['categories'],$newCategory);
            }
            array_push($newCollection,$data);
        }

        // $collection = collect([
        //     'services' => $services,
        //     'items' => $categoryWithItems
        // ]);

        return response()->json($newCollection);
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

    public function orderDefaults()
    {
        $appDefaults = AppDefault::first();
        $appDefaults['order_time'] = json_decode($appDefaults->order_time);
        $appDefaults['driver_notes'] = json_decode($appDefaults->driver_notes);

        $input = $appDefaults->only('order_time', 'driver_notes');

        return response()->json($input);
    }

    public function mainAreas()
    {
        $mainAreas = MainArea::pluck('name','id')->toArray();
        
        return response()->json($mainAreas);
    }

    public function offers()
    {
        $offers = Offer::where('status',1)->get();
        $url = asset('files/offer_banners/');
        $data = [
            'data' => $offers,
            'imageUrl' =>$url
        ];
        return response()->json($data);
    }
}
