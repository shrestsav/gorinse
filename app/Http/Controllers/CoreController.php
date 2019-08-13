<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Category;
use App\Service;

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

}
