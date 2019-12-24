<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

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
    public function all()
    {
        $customers = User::whereHas('roles', function ($query) {
                            $query->where('name', '=', 'customer');
                          })
                         ->whereNotNull('fname')
                         ->whereNotNull('lname')
                         ->get();

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
