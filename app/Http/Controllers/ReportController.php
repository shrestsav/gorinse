<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use Excel;
use App\Exports\Report;

class ReportController extends Controller
{
    public function totalOrders(Request $request)
    {
      $this->validate($request, [
            'type' => 'required|string',
        ]);

        $collection = [];

        if($request->type=='monthly'){
          $this->validate($request, [
              'year_month' => 'required|string'
          ]);

          $year_month = explode('-',$request->year_month);
          $days = $this->days_in_month($year_month[0],$year_month[1]);

          $labels = [];
          $data   = [];
          foreach($days as $date => $day){
            $orders = Order::whereYear('created_at', '=', $year_month[0])
                        ->whereMonth('created_at', '=', $year_month[1])
                        ->whereDay('created_at', '=', $date)
                        ->count();
                array_push($labels, $day);
                array_push($data, $orders);
                // $report[$date] = [
                //  'display' =>  $date,
                //  'data'    =>  $orders
                // ];
          } 

          $collection = collect([
            'type'     => $request->type,
            'year_month' => $request->year_month,
            'labels'   => $labels,
            'data'     => $data,
          ]);
        }
        if($request->type=='yearly'){
          $this->validate($request, [
              'year' => 'required|string',
          ]);

          $labels = [];
          $data   = [];
          
          $months = [
            '1'  => 'Jan',  
            '2'  => 'Feb',  
            '3'  => 'Mar',  
            '4'  => 'Apr',  
            '5'  => 'May',  
            '6'  => 'Jun',  
            '7'  => 'Jul',  
            '8'  => 'Aug',  
            '9'  => 'Sep',  
            '10' => 'Oct',  
            '11' => 'Nov',  
            '12' => 'Dec',  
          ];
          foreach($months as $value => $month){
            $orders = Order::whereYear('created_at', '=', $request->year)
                        ->whereMonth('created_at', '=', $value)
                        ->count();

                array_push($labels, $month);
                array_push($data, $orders);
          }

          $collection = collect([
            'type'    =>  $request->type,
            'year'    =>  $request->year,
            'labels'   => $labels,
            'data'     => $data,
          ]);
        }
      
      return $collection;
    }

    public function totalSales(Request $request)
    {
      $this->validate($request, [
        'type' => 'required|string',
      ]);

      $collection = [];

      if($request->type=='monthly'){
        $this->validate($request, [
            'year_month' => 'required|string'
        ]);

        $year_month = explode('-',$request->year_month);
        $days = $this->days_in_month($year_month[0],$year_month[1]);

        $labels = [];
        $data   = [];
        $grandTotal = 0;
        foreach($days as $date => $day){
          $orders = Order::where('status','>=',7)
                         ->whereYear('created_at', '=', $year_month[0])
                         ->whereMonth('created_at','=', $year_month[1])
                         ->whereDay('created_at',  '=', $date)
                         ->get()
                         ->makeVisible('total_amount')
                         ->toArray();
          array_push($labels, $day);
          $totalAmount = 0;
          if(count($orders)){
            foreach($orders as $order){
              if(array_key_exists("total_amount", $order))
                $totalAmount+=$order['total_amount'];
            }
          }
          $grandTotal+=$totalAmount;
          array_push($data, $totalAmount);
        } 

        $collection = collect([
          'type'       => $request->type,
          'year_month' => $request->year_month,
          'labels'     => $labels,
          'data'       => $data,
          'grandTotal' => $grandTotal
        ]);
      }
      if($request->type=='yearly'){
        $this->validate($request, [
            'year' => 'required|string',
        ]);

        $labels = [];
        $data   = [];
        
        $months = [
          '1'  => 'Jan',  
          '2'  => 'Feb',  
          '3'  => 'Mar',  
          '4'  => 'Apr',  
          '5'  => 'May',  
          '6'  => 'Jun',  
          '7'  => 'Jul',  
          '8'  => 'Aug',  
          '9'  => 'Sep',  
          '10' => 'Oct',  
          '11' => 'Nov',  
          '12' => 'Dec'  
        ];

        $grandTotal = 0;
        foreach($months as $value => $month){
          $orders = Order::where('status','>=',7)
                         ->whereYear('created_at', '=', $request->year)
                         ->whereMonth('created_at', '=', $value)
                         ->get()
                         ->makeVisible('total_amount')
                         ->toArray();

          array_push($labels, $month);
          $totalAmount = 0;
          if(count($orders)){
            foreach($orders as $order){
              if(array_key_exists("total_amount", $order))
                $totalAmount+=$order['total_amount'];
            }
          }
          $grandTotal+=$totalAmount;
          array_push($data, $totalAmount);
        }

        $collection = collect([
          'type'        =>  $request->type,
          'year'        =>  $request->year,
          'labels'      => $labels,
          'data'        => $data,
          'grandTotal'  => $grandTotal,
        ]);
      }
      
      return $collection;
    }

    public function totalCustomers(Request $request)
    {
    	$this->validate($request, [
            'type' => 'required|string',
        ]);

        $collection = [];

        if($request->type=='monthly'){
	        $this->validate($request, [
	            'year_month' => 'required|string'
	        ]);

	        $year_month = explode('-',$request->year_month);
	        $days = $this->days_in_month($year_month[0],$year_month[1]);

	        $labels = [];
	        $data 	= [];
        	foreach($days as $date => $day){
        		$orders = User::whereHas('roles', function ($query) {
                              $query->where('name', '=', 'customer');
                           })
                          ->whereYear('created_at', '=', $year_month[0])
                					->whereMonth('created_at', '=', $year_month[1])
                					->whereDay('created_at', '=', $date)
                					->count();
          	array_push($labels, $day);
          	array_push($data, $orders);
        	} 

        	$collection = collect([
        		'type'		 =>	$request->type,
        		'year_month' =>	$request->year_month,
        		'labels'	 =>	$labels,
        		'data'		 =>	$data,
        	]);
        }
        if($request->type=='yearly'){
        	$this->validate($request, [
	            'year' => 'required|string',
	        ]);

	        $labels = [];
	        $data 	= [];
	        
	        $months = [
	        	'1'  => 'Jan',	
	        	'2'  => 'Feb',	
	        	'3'  => 'Mar',	
	        	'4'  => 'Apr',	
	        	'5'  => 'May',	
	        	'6'  => 'Jun',	
	        	'7'  => 'Jul',	
	        	'8'  => 'Aug',	
	        	'9'  => 'Sep',	
	        	'10' => 'Oct',	
	        	'11' => 'Nov',	
	        	'12' => 'Dec',	
	        ];
	        foreach($months as $value => $month){
	        	$orders = User::whereHas('roles', function ($query) {
                              $query->where('name', '=', 'customer');
                           })
                          ->whereYear('created_at', '=', $request->year)
                					->whereMonth('created_at', '=', $value)
                					->count();
          	array_push($labels, $month);
          	array_push($data, $orders);
	        }

	        $collection = collect([
        		'type'		=>	$request->type,
        		'year'		=>	$request->year,
        		'labels'	 =>	$labels,
        		'data'		 =>	$data,
        	]);
        }
    	
    	return $collection;
    }
    
    /**
     * Returns all days of perticular month
     */
    public function days_in_month($year,$month)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("D-M-d", $mktime);
            $date = date("M-d", $mktime);
            $dates_month[$i] = $date;
        }

        return $dates_month;
    }

    public function export(Request $request)
    {
      // return $request->all();
      $this->validate($request, [
        'report'  => 'required|string',
      ]);
      
      $report = $request->report;

      if($report=='deliveredTimewise'){
        $this->validate($request, [
          'type' => 'required|string',
        ]);

        $collection = [];

        if($request->type=='monthly'){
          $this->validate($request, [
              'year_month' => 'required|string'
          ]);

          $year_month = explode('-',$request->year_month);

          $data = Order::select('orders.id',
                                'orders.customer_id',
                                'orders.type',
                                'orders.status',
                                'orders.created_at',
                                'users.fname',
                                'users.lname',
                                'PL.name as pick_location',
                                'DL.name as drop_location'
                              )
                        ->join('users','users.id','=','orders.customer_id')
                        ->join('user_addresses as PL','PL.id','=','orders.pick_location')
                        ->join('user_addresses as DL','DL.id','=','orders.pick_location')
                        ->where('orders.status','>=',7)
                        ->whereYear('orders.created_at', '=', $year_month[0])
                        ->whereMonth('orders.created_at','=', $year_month[1])
                        ->get()
                        ->makeVisible('total_amount')
                        // ->makeHidden(['id','type'])
                        ->toArray();
          return $data;
          $head = ['NAME','ROLE','EMAIL'];



          // $days = $this->days_in_month($year_month[0],$year_month[1]);

          // $labels = [];
          // $data   = [];
          // $grandTotal = 0;
          // foreach($days as $date => $day){
          //   $orders = Order::where('status','>=',7)
          //                  ->whereYear('created_at', '=', $year_month[0])
          //                  ->whereMonth('created_at','=', $year_month[1])
          //                  ->whereDay('created_at',  '=', $date)
          //                  ->get()
          //                  ->makeVisible('total_amount')
          //                  ->toArray();
          //   array_push($labels, $day);
          //   $totalAmount = 0;
          //   if(count($orders)){
          //     foreach($orders as $order){
          //       if(array_key_exists("total_amount", $order))
          //         $totalAmount+=$order['total_amount'];
          //     }
          //   }
          //   $grandTotal+=$totalAmount;
          //   array_push($data, $totalAmount);
          // } 

          // $collection = collect([
          //   'type'       => $request->type,
          //   'year_month' => $request->year_month,
          //   'labels'     => $labels,
          //   'data'       => $data,
          //   'grandTotal' => $grandTotal
          // ]);
        }
        if($request->type=='yearly'){
          $this->validate($request, [
              'year' => 'required|string',
          ]);

          $labels = [];
          $data   = [];
          
          $months = [
            '1'  => 'Jan',  
            '2'  => 'Feb',  
            '3'  => 'Mar',  
            '4'  => 'Apr',  
            '5'  => 'May',  
            '6'  => 'Jun',  
            '7'  => 'Jul',  
            '8'  => 'Aug',  
            '9'  => 'Sep',  
            '10' => 'Oct',  
            '11' => 'Nov',  
            '12' => 'Dec'  
          ];

          $grandTotal = 0;
          foreach($months as $value => $month){
            $orders = Order::where('status','>=',7)
                           ->whereYear('created_at', '=', $request->year)
                           ->whereMonth('created_at', '=', $value)
                           ->get()
                           ->makeVisible('total_amount')
                           ->toArray();

            array_push($labels, $month);
            $totalAmount = 0;
            if(count($orders)){
              foreach($orders as $order){
                if(array_key_exists("total_amount", $order))
                  $totalAmount+=$order['total_amount'];
              }
            }
            $grandTotal+=$totalAmount;
            array_push($data, $totalAmount);
          }

          $collection = collect([
            'type'        =>  $request->type,
            'year'        =>  $request->year,
            'labels'      => $labels,
            'data'        => $data,
            'grandTotal'  => $grandTotal,
          ]);
        }










        //  $orders = Order::where('status','>=',7)
        //                  ->whereYear('created_at', '=', $year_month[0])
        //                  ->whereMonth('created_at','=', $year_month[1])
        //                  ->whereDay('created_at',  '=', $date)
        //                  ->get()
        //                  ->makeVisible('total_amount')
        //                  ->toArray();
        // $data = User::select('users.name',
        //                     'users.user_type',
        //                     'users.email',
        //                     'user_details.gender',
        //                     'user_details.address',
        //                     'user_details.contact',
        //                     'user_details.date_of_birth',
        //                     'user_details.hourly_rate',
        //                     'user_details.annual_salary',
        //                     'user_details.description',
        //                     'user_details.employment_start_date')
        //                   ->join('user_details','user_details.user_id','=','users.id')
        //                   ->whereHas('roles', function ($query) use ($report) {
        //                       $query->where('name', '=', $report);
        //                     })
        //                   ->get();
        // $head = ['NAME','ROLE','EMAIL','GENDER','ADDRESS','CONTACT','DOB','HOURLY RATE','ANNUAL SALARY','DESCRIPTION','START DATE'];
      }

      if(!count($data)){
        return back()->with('error','Table Seems to be Empty');
      }

      return Excel::download(new Report($data,$head), $report.'.xlsx');
    }
}
