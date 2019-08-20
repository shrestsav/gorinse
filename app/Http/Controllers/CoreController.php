<?php

namespace App\Http\Controllers;

use App\AppDefault;
use App\Category;
use App\Item;
use App\Service;
use Illuminate\Http\Request;

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

    public function appDefaults()
    {
        $appDefaults = AppDefault::first();
        $appDefaults['company_logo_url'] = asset('files/'.$appDefaults->company_logo);
        $appDefaults['order_time'] = json_decode($appDefaults->order_time);
        $appDefaults['driver_notes'] = json_decode($appDefaults->driver_notes);
        $appDefaults['online_chat'] = json_decode($appDefaults->online_chat);
        
        return response()->json($appDefaults);
    }
    public function updateAppDefaults(Request $request)
    {
        $input = [];
        if($request->saveType=='generalSetting'){
            $input = $request->only('VAT', 'delivery_charge','OTP_expiry');
        }
        if($request->saveType=='supportSetting'){
            $input = $request->only('company_logo', 'company_email', 'hotline_contact', 'FAQ_link', 'online_chat');
            $input['online_chat'] = json_encode($input['online_chat']);
        }
        if($request->saveType=='orderSetting'){
            $input['order_time'] = json_encode($request->order_time);
            $input['driver_notes'] = json_encode($request->driver_notes);
        }
        
        $update = AppDefault::where('id',$request->id)->update($input);

        return response()->json('Successfully Updated');
    }

}
