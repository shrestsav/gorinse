<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = User::whereHas('roles', function ($query) {
                            $query->where('name', '=', 'customer');
                          })
                         ->whereNotNull('fname')
                         ->whereNotNull('lname')
                         ->paginate(Session::get('rows'));

        return $customers;
    }

    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function unverifiedCustomers()
    {
        $customers = User::whereHas('roles', function ($query) {
                            $query->where('name', '=', 'customer');
                          })
                         ->whereNull('fname')
                         ->whereNull('lname')
                         ->paginate(Session::get('rows'));

        return $customers;
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
        //
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

    public function deleteCustomers(Request $request)
    {
        $customers = User::whereIn('id',$request->customerIds)->with('orders')->get();
        
        foreach($customers as $customer){
            if(count($customer->orders)){
                return response()->json(['message'=>'One of the customer has existing orders, you cannot delete this customer'],404);
            }
        }

        $customers = User::whereIn('id',$request->customerIds)->delete();

        return response()->json(['message'=>'Customer account has been removed']);
    }

    public function address($customer_id)
    {
        $address = UserAddress::where('user_id',$customer_id)->get();
        return response()->json($address);
    }

}
