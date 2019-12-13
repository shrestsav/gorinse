<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class DriverController extends Controller
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = User::whereHas('roles', function ($query) {
                            $query->where('name', '=', 'driver');
                          })
                         ->paginate(Session::get('rows'));

        return $drivers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required|max:255',
            'email' => 'email|max:255|unique:users',
            'phone' => 'required|unique:users',
            'area_id' => 'required|numeric',
        ]);
        $driver = User::create($request->all()); 
        $role_id = Role::where('name','driver')->first()->id;
        $request['user_id'] = $driver->id;
        $driverDetails = UserDetail::create($request->all()); 
        
        // Assign as Driver
        $driver->attachRole($role_id);

        return response()->json(['message'=>'Successfully Added']);
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
        $validator = Validator::make($request->all(), [
            "id" => 'required',
            "fname" => 'required|max:255',
            "lname" => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $error,
            ], 422);
        }

        $customerUpdate = User::findOrFail($request->id)
                              ->update([
                                'fname' =>  $request->fname,
                                'lname' =>  $request->lname
                              ]);

        return response()->json([
            'status' => '200',
            'message' => 'Updated Successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
