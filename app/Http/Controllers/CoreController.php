<?php

namespace App\Http\Controllers;

use App\AppDefault;
use App\Category;
use App\Item;
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
        
        $update = AppDefault::where('id',$request->id)->update($input);

        return response()->json('Successfully Updated');
    }

}
