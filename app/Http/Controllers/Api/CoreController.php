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
    public function serviceWithItems()
    {
        $services = Service::all();

        $categoryWithItems = Category::select('id','name','icon')->with('items:id,category_id,name')->get();

        $collection = collect([
            'services' => $services,
            'categories' => $categoryWithItems,
            'iconURL' => asset('files/categories/')
        ]);
        return response()->json($collection);
    }    

    public function servicesPlusItems()
    {
        $services = Service::all();
        $categoryWithItems = Category::select('id','name','icon')->with('items')->get();
        
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
                    'icon'  =>  asset('files/categories').'/'.$category->icon,
                    'items' =>  [],
                ];
                foreach ($category->items as $item) {
                    $newItem = [];
                    $newItem = [
                        'id'    =>  $item->id,
                        'name'  =>  $item->name,
                        'price' =>  $item->price+$serviceCharge,
                    ];
                    array_push($newCategory['items'],$newItem);
                }
                array_push($data['categories'],$newCategory);
            }
            array_push($newCollection,$data);
        }

        // $collection = collect([
        //     'services' => $newCollection,
        //     'items'    => $categoryWithItems
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
        $appDefaults = AppDefault::firstOrFail();
        $appDefaults['company_logo'] = asset('files/'.$appDefaults->company_logo);

        $input = $appDefaults->only('company_logo', 'company_email', 'hotline_contact', 'FAQ_link', 'online_chat');

        return response()->json($input);
    }    

    public function orderDefaults()
    {
        $appDefaults = AppDefault::firstOrFail();

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

    public function termsAndConditions()
    {
        $TACS = AppDefault::firstOrFail()->TACS;
        
        return response()->json($TACS);
    }

    public function FAQS()
    {
        $FAQS = AppDefault::firstOrFail()->FAQS;
        
        return response()->json($FAQS);
    }

    public function orderTypeDesc()
    {
        $OTD = AppDefault::firstOrFail()->OTD;
        
        return response()->json($OTD);
    }

    public function suraj(Request $request)
    {
        return response()->json([
            'message'   => 'This is what i got',
            'yourdata'  =>  $request->all()
        ]);
    }
}
