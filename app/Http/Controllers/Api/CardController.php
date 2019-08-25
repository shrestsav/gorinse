<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\PaymentCard;
use Illuminate\Http\Request;
use Validator;
use Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = PaymentCard::where('user_id',Auth::id())->get();
        return response()->json($cards);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|digits:1',
            'card_no' => 'required|digits:3',
            'month_year' => 'required',
            'csv' => 'required|digits:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $request['user_id'] = Auth::id();
        $card = PaymentCard::create($request->all());
        
        if($card){
            return response()->json([
                "status" => "200",
                "message" => "Card Saved Successfully"
            ], 200);
        }
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
        $check = PaymentCard::findOrFail($id);

        $check->delete();
        return response()->json([
            "status" => "200",
            "message" => "Card Removed Successfully"
        ], 200);
    }
}
