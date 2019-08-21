<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\UserDetail;
use Illuminate\Http\Request;

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
        $drivers = $this->user->driverList();
        return $drivers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
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
