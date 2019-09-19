<?php

namespace App\Http\Controllers;

use App\AppDefault;
use App\Category;
use App\Item;
use App\MainArea;
use App\Offer;
use App\Service;
use Illuminate\Http\Request;
use Validator;

class CoreController extends Controller
{
    public function getFields($fieldType)
    {
    	if($fieldType!=''){
	    	$fields = config('fields.'.$fieldType);
	    	return $fields;
	    }
	    else{
	    	return json_encode('Field Type Required');
	    }
    }
    public function getSettings($settingType)
    {
    	if($settingType!=''){
	    	$settings = config('settings.'.$settingType);
	    	return $settings;
	    }
	    else{
	    	return json_encode('Setting Type Required');
	    }
    }

    public function services(Request $request)
    {
        $services = Service::all();
        return response()->json($services);
    }

    public function addService(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:services',
            'price' => 'required|numeric',
        ]);

        $service = Service::create($request->all());
        return response()->json('Successfully Added');
    }

    public function categories(Request $request)
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function addCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories',
            'icon' => 'required|string',
        ]);

        $category = Category::create($request->all());
        return response()->json('Successfully Added');
    }

    public function items(Request $request)
    {
        $items = Item::with('category')->get();
        return response()->json($items);
    }

    public function addItem(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|numeric',
            'name' => 'required|unique:items',
            'price' => 'required|numeric',
        ]);

        $item = Item::create($request->all());
        return response()->json('Successfully Added');
    }

    public function mainAreas()
    {
        $mainAreas = MainArea::all();
        return response()->json($mainAreas);
    }

    public function addMainArea(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:main_areas',
        ]);

        $mainArea = MainArea::create($request->all());
        return response()->json('Successfully Added');
    }

    public function deleteMainArea($id)
    {
        $mainArea = MainArea::findOrFail($id);

        $mainArea->delete();
        return response()->json('Main Area Deleted');
    }

    public function offers()
    {
        $offers = Offer::orderBy('id','DESC')->get();
        return response()->json($offers);
    }

    public function addOffer(Request $request)
    {
        $validatedData = $request->validate([
            'offer_name' => 'required|string',
            'offer_description' => 'required|string',
            'offer_image' => 'required|mimes:jpeg,png|max:3072',
        ]);

        $offer = new Offer();
        $offer->name = $request['offer_name'];
        $offer->description = $request['offer_description'];
        $offer->status = $request['status'];

        $offer->save();
            
        $image = $request->file('offer_image');
        $fileName = 'banner_offer_'.$offer->id.'.'.$image->getClientOriginalExtension();
        $uploadDirectory = public_path('files'.DS.'offer_banners');
        $image->move($uploadDirectory, $fileName);

        Offer::where('id',$offer->id)->update(['image' => $fileName]);
        
        return response()->json('Successfully Added');
    }

    public function editOffer(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $offer = Offer::findOrFail($id);
        $offer->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'status' => $request['status']
        ]);

        if ($request->hasFile('offer_image')) {
            $validator = Validator::make($request->all(), [
                'offer_image' => 'required|mimes:jpeg,png|max:3072',
            ]);
            $image = $request->file('offer_image');
            $fileName = 'banner_offer_'.$offer->id.'.'.$image->getClientOriginalExtension();
            $uploadDirectory = public_path('files'.DS.'offer_banners');
            $image->move($uploadDirectory, $fileName);

            $offer->update(['image' => $fileName]);
        }
        
        return response()->json('Successfully Updated');
    }

    public function changeOfferStatus(Request $request)
    {
        $validatedData = $request->validate([
            'status' => 'required|digits:1',
            'id' => 'required|numeric',
        ]);
        $input = $request->only('status', 'id');

        $update = Offer::where('id',$input['id'])->update(['status' => $input['status']]);
        
        return response()->json('Status Changed');
    }
    
    public function deleteOffer($id)
    {
        $offer = Offer::findOrFail($id);

        $offer->delete();
        return response()->json('Offer Deleted');
    }

    public function appDefaults()
    {
        $appDefaults = AppDefault::first();
        $appDefaults['company_logo_url'] = asset('files/'.$appDefaults->company_logo);        
        return response()->json($appDefaults);
    }
    
    public function updateAppDefaults(Request $request)
    {
        $input = [];
        if($request->saveType=='generalSetting'){
            $validatedData = $request->validate([
                'VAT' => 'required|numeric',
                'delivery_charge' => 'required|numeric',
                'EDT' => 'required|numeric',
                'OTP_expiry' => 'required|numeric',
                'app_rows' => 'required|numeric',
                'sys_rows' => 'required|numeric',
            ]);

            $input = $request->only('VAT', 'delivery_charge','EDT','OTP_expiry','app_rows','sys_rows');
        }
        if($request->saveType=='supportSetting'){
            $validatedData = $request->validate([
                'logoFile' => 'mimes:jpeg,png,jpg|max:6144',
                'company_logo' => 'required|string|max:255',
                'company_email' => 'required|string|email|max:255',
                'hotline_contact' => 'required|string|max:255',
                'FAQ_link' => 'required|string|max:255',
                'online_chat' => 'required',
            ]);

            $input = $request->only('company_logo', 'company_email', 'hotline_contact', 'FAQ_link', 'online_chat');
            $input['online_chat'] = json_decode($request->online_chat,true);

            if ($request->hasFile('logoFile')) {
                $validator = Validator::make($request->all(), [
                    "logoFile" => 'mimes:jpeg,bmp,png|max:3072',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => '422',
                        'message' => 'Validation Failed',
                        'errors' => $validator->errors(),
                    ], 422);
                }
                $photo = $request->file('logoFile');
                $fileName = 'company_logo.'.$photo->getClientOriginalExtension();
                $uploadDirectory = public_path('files');
                $photo->move($uploadDirectory, $fileName);

                $input['company_logo'] = $fileName;
            }
        }
        if($request->saveType=='orderSetting'){
            $input = $request->only('order_time', 'driver_notes');
        }
        if($request->saveType=='TACS'){
            $input = $request->only('TACS');
        }
        if($request->saveType=='FAQS'){
            $input = $request->only('FAQS');
        }
        
        $update = AppDefault::firstOrFail()->update($input);

        return response()->json('Successfully Updated');
    }

    public function orderTime()
    {
        $appDefaults = AppDefault::first();
        $orderTime = $appDefaults->order_time;

        return response()->json($orderTime);
    }
}
