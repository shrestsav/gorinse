<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;

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
                         ->whereMonth('created_at', '=', $year_month[1])
                         ->whereDay('created_at', '=', $date)
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

    public function test()
    {
      $type = 'monthly';
      $year_month = '2019-09';
      $year = '2019';

      $collection = [];

      if($type=='monthly'){

        $year_month = explode('-',$year_month);
        $days = $this->days_in_month($year_month[0],$year_month[1]);

        $labels = [];
        $data   = [];
        foreach($days as $date => $day){
          $orders = Order::where('status','>=',7)
                         ->whereYear('created_at', '=', $year_month[0])
                         ->whereMonth('created_at', '=', $year_month[1])
                         ->whereDay('created_at', '=', $date)
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
          array_push($data, $totalAmount);

          
          
              // $report[$date] = [
              //  'display' =>  $date,
              //  'data'    =>  $orders
              // ];
        } 

        $collection = collect([
          'type'     => $type,
          'year_month' => $year_month,
          'labels'   => $labels,
          'data'     => $data,
        ]);
      }
      if($type=='yearly'){

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
          $orders = Order::whereYear('created_at', '=', $year)
                      ->whereMonth('created_at', '=', $value)
                      ->count();

          array_push($labels, $month);
          array_push($data, $orders);
        }

        $collection = collect([
          'type'    =>  $type,
          'year'    =>  $year,
          'labels'   => $labels,
          'data'     => $data,
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
}
