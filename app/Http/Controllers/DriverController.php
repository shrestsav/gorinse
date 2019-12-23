<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Order;
use App\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;
use Intervention\Image\Facades\Image;

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
        $drivers = User::select('users.id',
                                'users.fname',
                                'users.lname',
                                'users.username',
                                'users.email',
                                'users.phone',
                                'UD.photo',
                                'UD.gender',
                                'UD.area_id',
                                'UD.dob',
                                'UD.photo',
                                'UD.joined_date',
                                'UD.description')
                        ->whereHas('roles', function ($query) {
                            $query->where('name', '=', 'driver');
                        })
                        ->join('user_details as UD','UD.user_id','=','users.id')
                        ->paginate(Session::get('rows'));

        return response()->json($drivers);
    }

    public function allDrivers()
    {
        $drivers = User::select('id', 'fname', 'lname')
                        ->whereHas('roles', function ($query) {
                            $query->where('name', '=', 'driver');
                        })
                        ->get();

        return response()->json($drivers);
    }

    public function driverOrders(Request $request, $driver_id)
    {
        $this->validate($request, [
            'type' => 'required|string',
        ]);

        $orders = Order::where(function ($query) use ($driver_id){
                            $query->where('driver_id',$driver_id)
                                  ->orWhere('drop_driver_id',$driver_id);
                        })
                        ->with('details','customer:id,fname,lname','pick_location_details.mainArea');

        if($request->type=='monthly'){
            $this->validate($request, [
                'year_month' => 'required|string'
            ]);
            $year_month = explode('-',$request->year_month);
            $orders = $orders->whereYear('created_at', '=', $year_month[0])
                             ->whereMonth('created_at','=', $year_month[1]);
        }
        elseif($request->type=='yearly'){
          $this->validate($request, [
              'year' => 'required|string',
          ]);
          $orders = $orders->whereYear('created_at', '=', $request->year);
        }

        $orders = $orders->paginate(Session::get('rows'));
                        
        $orders->setCollection( $orders->getCollection()->makeVisible('total_amount'));

        return response()->json([
            'orders'      =>  $orders,
            'orderStatus' => config('settings.orderStatuses')
        ]);
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
            'id'      => 'required',
            'fname'   => 'required|max:255',
            'lname'   => 'required|max:255',
            'email'   => 'email|max:255|unique:users,email,'.$id,
            'phone'   => 'required|unique:users,phone,'.$id,
            'area_id' => 'required|numeric',
            'photo_file' => 'mimes:jpeg,jpg,bmp,png|max:15072'
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
                                'fname'     =>  $request->fname,
                                'lname'     =>  $request->lname,
                                'username'  =>  $request->username,
                                'phone'     =>  $request->phone,
                                'email'     =>  $request->email
                              ]);

        $updateDetailsFields = [
            'area_id'     => $request->area_id, 
            'dob'         => $request->dob,
            'joined_date' => $request->joined_date,
            'description' => $request->description
        ];

        //Save User Photo 
        if ($request->hasFile('photo_file')) {
            $image = Image::make($request->file('photo_file'))->orientate();
            // prevent possible upsizing
            $image->resize(null, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $fileName = 'dp_user_'.$id.'.jpg';
            $uploadDirectory = public_path('files'.DS.'users'.DS.$id);
            if (!file_exists($uploadDirectory)) {
                \File::makeDirectory($uploadDirectory, 0755, true);
            }
            $image->save($uploadDirectory.DS.$fileName,60);

            $updateDetailsFields['photo'] = $fileName;

        } 

        $detailsUpdate = UserDetail::updateOrCreate(['user_id' => $request->id], $updateDetailsFields);
        
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
