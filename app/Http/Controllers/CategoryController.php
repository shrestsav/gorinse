<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all()->makeVisible(['can_delete','icon_src']);
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validatedData = $request->validate([
            'name'        => 'required|max:20|unique:categories',
            'description' => 'required|max:100',
            'icon_file'   => 'required|mimetypes:image/svg',
        ]);

        if($request->hasFile('icon_file')) {
            $icon = $request->file('icon_file');
            $fileName = Str::random(15).'.'.$icon->getClientOriginalExtension();
            $uploadDirectory = public_path('files'.DS.'categories');
            $icon->move($uploadDirectory, $fileName);
        } 

        $category = Category::create([
            'name'         =>  $request->name,
            'description'  =>  $request->description,
            'icon'         =>  $fileName
        ]);
        return response()->json('Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id'          => 'required|numeric',
            'description' => 'required|max:100',
            'icon_file'   => 'mimetypes:image/svg',
        ]);

        $category = Category::findOrFail($request->id);
        $fileName = $category->icon;
        if($request->hasFile('icon_file')) {

            $file = public_path('files'.DS.'categories'.DS.$fileName);

            if(file_exists($file)){
                \File::delete($file);
            }

            $icon = $request->file('icon_file');
            $fileName = Str::random(15).'.'.$icon->getClientOriginalExtension();
            $uploadDirectory = public_path('files'.DS.'categories');
            $icon->move($uploadDirectory, $fileName);
        }

        
        $category->description = $request->description;
        $category->icon = $fileName;
        $category->save();

        return response()->json('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id)->makeVisible('can_delete');

        if($category['can_delete']){
            $file = public_path('files'.DS.'categories'.DS.$category->icon);

            if(file_exists($file)){
                \File::delete($file);
            }
            $category->delete();

            return response()->json([
                'message' => 'Successfully Deleted'
            ]);
        }

        return response()->json([
            'message' => 'Sorry You cannot delete this'
        ], 403);
    }
}
