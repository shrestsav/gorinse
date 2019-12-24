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
          $grandTotal = 0;
          foreach($days as $date => $day){
            $orders = Order::whereYear('created_at', '=', $year_month[0])
                        ->whereMonth('created_at', '=', $year_month[1])
                        ->whereDay('created_at', '=', $date)
                        ->count();
            array_push($labels, $day);
            array_push($data, $orders);

            $grandTotal+=$orders;
          } 

          $collection = collect([
            'type'        => $request->type,
            'year_month'  => $request->year_month,
            'labels'      => $labels,
            'data'        => $data,
            'grandTotal'  => $grandTotal
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
          $grandTotal = 0;
          foreach($months as $value => $month){
            $orders = Order::whereYear('created_at', '=', $request->year)
                            ->whereMonth('created_at', '=', $value)
                            ->count();

            array_push($labels, $month);
            array_push($data, $orders);
            $grandTotal+=$orders;
          }

          $collection = collect([
            'type'        =>  $request->type,
            'year'        =>  $request->year,
            'labels'      =>  $labels,
            'data'        =>  $data,
            'grandTotal'  =>  $grandTotal
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

        $grandTotal = 0;
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
          $grandTotal+=$orders;
      	} 

      	$collection = collect([
      		'type'		    =>	$request->type,
      		'year_month'  =>	$request->year_month,
      		'labels'	    =>	$labels,
      		'data'		    =>	$data,
          'grandTotal'  =>  $grandTotal
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
        $grandTotal = 0;
        foreach($months as $value => $month){
        	$orders = User::whereHas('roles', function ($query) {
                            $query->where('name', '=', 'customer');
                         })
                        ->whereYear('created_at', '=', $request->year)
              					->whereMonth('created_at', '=', $value)
              					->count();
        	array_push($labels, $month);
        	array_push($data, $orders);
          $grandTotal+=$orders;
        }

        $collection = collect([
      		'type'		    =>	$request->type,
      		'year'		    =>	$request->year,
      		'labels'	    =>	$labels,
      		'data'		    =>	$data,
          'grandTotal'  =>  $grandTotal
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
      $this->validate($request, [
        'report'  => 'required|string'
      ]);
      
      $report = $request->report;

      if($report=='deliveredOrders'){
        return $this->deliveredOrders($request);
      }

      elseif($report=='driverOrders'){
        return $this->driverOrders($request);
      }

      elseif($report=='newCustomers'){
        return $this->newCustomers($request);
      }

      elseif($report=='orders'){
        return $this->orders($request);
      }

      else{
        return 'Error';
      }


    }

    public function newCustomers($request)
    {
      $this->validate($request, [
        'type' => 'required|string'
      ]);

      $collection = [];

      $data = User::select('id',
                            'fname',
                            'lname',
                            'created_at')
                    ->whereHas('roles', function ($query) {
                      $query->where('name', '=', 'customer');
                    });

      if($request->type=='monthly'){
        $this->validate($request, [
          'year_month' => 'required|string'
        ]);
        $fileName = 'New Customers Monthly-'.$request->year_month;
        $year_month = explode('-',$request->year_month);

        $data = $data->whereYear('created_at', '=', $year_month[0])
                     ->whereMonth('created_at','=', $year_month[1]);
      }

      if($request->type=='yearly'){
        $this->validate($request, [
          'year' => 'required|string',
        ]);
        $fileName = 'New Customers Yearly-'.$request->year;
        $data = $data->whereYear('created_at', '=', $request->year);
      }

      $data = $data->get()
                   ->makeHidden('full_name')
                   ->toArray();

      $head = [
        'CUSTOMER ID',
        'FIRST NAME',
        'LAST NAME',
        'JOINED'
      ];

      if(!count($data)){
        return back()->with('error','Empty Data');
      }

      return Excel::download(new Report($data,$head), $fileName.'.xlsx');
    }

    public function driverOrders($request)
    {
      $this->validate($request, [
        'driver_id'  => 'required|numeric'
      ]);

      $driver_id = $request->driver_id;

      $orders = Order::where(function ($query) use ($driver_id){
                          $query->where('driver_id',$driver_id)
                                ->orWhere('drop_driver_id',$driver_id);
                      })
                      ->with('customer:id,fname,lname,phone',
                             'details:order_id,PFC,DTC,PT',
                             'pickDriver:id,fname,lname,phone',
                             'dropDriver:id,fname,lname,phone',
                             'orderItems:order_id,service_id',
                             'orderItems.service:id,name',
                             'pick_location_details:id,area_id',
                             'pick_location_details.mainArea:id,name');

      if($request->type=='monthly'){
        $this->validate($request, [
            'year_month' => 'required|string'
        ]);
        $fileName = 'Monthly-'.$request->year_month;
        $year_month = explode('-',$request->year_month);

        $orders = $orders->whereYear('orders.created_at', '=', $year_month[0])
                         ->whereMonth('orders.created_at','=', $year_month[1]);
      }

      if($request->type=='yearly'){
        $this->validate($request, [
            'year' => 'required|string',
        ]);
        $fileName = 'Yearly-'.$request->year;
        $orders = $orders->whereYear('orders.created_at', '=', $request->year);
      }

      $orders = $orders->get()
                       ->makeVisible('total_amount')
                       ->toArray();

      $data = [];

      foreach ($orders as $order) {
        $dataArr = [];

        $dataArr['id'] = $order['id'];

        $job = '';
        if($order['driver_id']==$driver_id && $order['drop_driver_id']==$driver_id)
          $job = 'Pick and Drop';
        elseif($order['driver_id']==$driver_id && $order['drop_driver_id']!=$driver_id)
          $job = 'Pick';
        if($order['driver_id']!=$driver_id && $order['drop_driver_id']==$driver_id)
          $job = 'Drop';
        $dataArr['job'] = $job;

        $orderType = 'Not Mentioned';
        if($order['type']==1)
          $orderType = 'Normal';
        elseif($order['type']==2)
          $orderType = 'Urgent';

        $dataArr['type'] = $orderType;

        $dataArr['ordered_date'] = \Carbon\Carbon::parse($order['created_at'])->format('M-d-Y');
        $dataArr['customer_name'] = $order['customer']['full_name'];
        $dataArr['customer_phone'] = ' '.$order['customer']['phone'];
        $dataArr['PFC'] = \Carbon\Carbon::parse($order['details']['PFC'])->format('M-d-Y');
        $dataArr['PDN'] = $order['pick_driver']['full_name'];
        $dataArr['DTC'] = \Carbon\Carbon::parse($order['details']['DTC'])->format('M-d-Y');
        $dataArr['DDN'] = $order['drop_driver']['full_name'];
        $dataArr['MA'] = $order['pick_location_details']['main_area']['name'];

        $paymentType = 'Not Mentioned';
        if($order['details']['PT']==1)
          $paymentType = 'Cash on Delivery';
        elseif($order['details']['PT']==2)
          $paymentType = 'Card';
        elseif($order['details']['PT']==3)
          $paymentType = 'Paypal';

        $dataArr['PT'] = $paymentType;

        $st = '';
        if($order['order_items']){
          $st = $order['order_items'][0]['service']['name'];
        }

        $dataArr['ST'] = $st;            
        $dataArr['amount'] = 'AED '.$order['total_amount'];

        array_push($data, $dataArr);

      }

      $head = [
        'ORDER ID',
        'JOB',
        'TYPE',
        'ORDERED DATE',
        'CUSTOMER NAME',
        'CUSTOMER CONTACT',
        'PICKED AT',
        'PICK DRIVER',
        'DELIVERED At',
        'DROP DRIVER',
        'MAIN AREA',
        'PAYMENT TYPE',
        'SERVICE TYPE',
        'TOTAL AMOUNT'
      ];

      if(!count($data)){
        return back()->with('error','Empty Data');
      }

      return Excel::download(new Report($data,$head), $fileName.'.xlsx');
    }

    public function deliveredOrders($request)
    {
      $this->validate($request, [
        'type' => 'required|string'
      ]);

      $collection = [];

      $orders = Order::select('id',
                              'customer_id',
                              'type',
                              'driver_id',
                              'drop_driver_id',
                              'pick_location',
                              'status',
                              'created_at')
                      ->with('customer:id,fname,lname,phone',
                         'details:order_id,PFC,DTC,PT',
                         'pickDriver:id,fname,lname,phone',
                         'dropDriver:id,fname,lname,phone',
                         'orderItems:order_id,service_id',
                         'orderItems.service:id,name',
                         'pick_location_details:id,area_id',
                         'pick_location_details.mainArea:id,name')
                      ->where('orders.status','>=',7);

      if($request->type=='monthly'){
        $this->validate($request, [
            'year_month' => 'required|string'
        ]);
        $fileName = 'Monthly-'.$request->year_month;
        $year_month = explode('-',$request->year_month);

        $orders = $orders->whereYear('orders.created_at', '=', $year_month[0])
                         ->whereMonth('orders.created_at','=', $year_month[1]);
      }

      if($request->type=='yearly'){
        $this->validate($request, [
            'year' => 'required|string',
        ]);
        $fileName = 'Yearly-'.$request->year;
        $orders = $orders->whereYear('created_at', '=', $request->year);
      }

      $orders = $orders->get()
                       ->makeVisible('total_amount')
                       ->toArray();

      $data = [];

      foreach($orders as $order){
        
        $dataArr = [];

        $dataArr['id'] = $order['id'];

        $orderType = 'Not Mentioned';
        if($order['type']==1)
          $orderType = 'Normal';
        elseif($order['type']==2)
          $orderType = 'Urgent';

        $dataArr['type']            = $orderType;
        $dataArr['ordered_date']    = \Carbon\Carbon::parse($order['created_at'])->format('M-d-Y');
        $dataArr['customer_name']   = $order['customer']['full_name'];
        $dataArr['customer_phone']  = ' '.$order['customer']['phone'];
        $dataArr['PFC']             = \Carbon\Carbon::parse($order['details']['PFC'])->format('M-d-Y');
        $dataArr['PDN']             = $order['pick_driver']['full_name'];
        $dataArr['DTC']             = \Carbon\Carbon::parse($order['details']['DTC'])->format('M-d-Y');
        $dataArr['DDN']             = $order['drop_driver']['full_name'];
        $dataArr['MA']              = $order['pick_location_details']['main_area']['name'];

        $paymentType = 'Not Mentioned';
        if($order['details']['PT']==1)
          $paymentType = 'Cash on Delivery';
        elseif($order['details']['PT']==2)
          $paymentType = 'Card';
        elseif($order['details']['PT']==3)
          $paymentType = 'Paypal';

        $dataArr['PT'] = $paymentType;
        $st = '';
        if($order['order_items']){
          $st = $order['order_items'][0]['service']['name'];
        }
        $dataArr['ST'] = $st;            
        $dataArr['amount'] = 'AED '.$order['total_amount'];

        array_push($data, $dataArr);
      }

      $head = [
        'ORDER ID',
        'TYPE',
        'ORDERED DATE',
        'CUSTOMER NAME',
        'CUSTOMER CONTACT',
        'PICKED AT',
        'PICK DRIVER',
        'DELIVERED At',
        'DROP DRIVER',
        'MAIN AREA',
        'PAYMENT TYPE',
        'SERVICE TYPE',
        'TOTAL AMOUNT'
      ];

      if(!count($data)){
        return back()->with('error','Empty Data');
      }

      return Excel::download(new Report($data,$head), $fileName.'.xlsx');
    }

    public function orders($request)
    {
      $this->validate($request, [
        'type' => 'required|string'
      ]);

      $collection = [];

      $orders = Order::select('id',
                              'customer_id',
                              'type',
                              'driver_id',
                              'drop_driver_id',
                              'pick_location',
                              'status',
                              'created_at')
                      ->with('customer:id,fname,lname,phone',
                         'details:order_id,PFC,DTC,PT',
                         'pickDriver:id,fname,lname,phone',
                         'dropDriver:id,fname,lname,phone',
                         'orderItems:order_id,service_id',
                         'orderItems.service:id,name',
                         'pick_location_details:id,area_id',
                         'pick_location_details.mainArea:id,name');

      if($request->type=='monthly'){
        $this->validate($request, [
            'year_month' => 'required|string'
        ]);

        $fileName = 'Monthly-'.$request->year_month;
        $year_month = explode('-',$request->year_month);

        $orders = $orders->whereYear('orders.created_at', '=', $year_month[0])
                         ->whereMonth('orders.created_at','=', $year_month[1]);
      }

      if($request->type=='yearly'){
        $this->validate($request, [
            'year' => 'required|string',
        ]);
        $fileName = 'Yearly-'.$request->year;
        $orders = $orders->whereYear('created_at', '=', $request->year);
      }

      $orders = $orders->get()
                       ->makeVisible('total_amount')
                       ->toArray();

      $data = [];

      foreach($orders as $order){
        
        $dataArr = [];

        $dataArr['id'] = $order['id'];

        $orderType = 'Not Mentioned';
        if($order['type']==1)
          $orderType = 'Normal';
        elseif($order['type']==2)
          $orderType = 'Urgent';

        $dataArr['type']            = $orderType;
        $dataArr['ordered_date']    = \Carbon\Carbon::parse($order['created_at'])->format('M-d-Y');
        $dataArr['customer_name']   = $order['customer']['full_name'];
        $dataArr['customer_phone']  = ' '.$order['customer']['phone'];
        $dataArr['PFC']             = \Carbon\Carbon::parse($order['details']['PFC'])->format('M-d-Y');
        $dataArr['PDN']             = $order['pick_driver']['full_name'];
        $dataArr['DTC']             = \Carbon\Carbon::parse($order['details']['DTC'])->format('M-d-Y');
        $dataArr['DDN']             = $order['drop_driver']['full_name'];
        $dataArr['MA']              = $order['pick_location_details']['main_area']['name'];

        $paymentType = 'Not Mentioned';
        if($order['details']['PT']==1)
          $paymentType = 'Cash on Delivery';
        elseif($order['details']['PT']==2)
          $paymentType = 'Card';
        elseif($order['details']['PT']==3)
          $paymentType = 'Paypal';

        $dataArr['PT'] = $paymentType;
        $st = '';
        if($order['order_items']){
          $st = $order['order_items'][0]['service']['name'];
        }
        $dataArr['ST'] = $st;            
        $dataArr['amount'] = 'AED '.$order['total_amount'];

        array_push($data, $dataArr);
      }

      $head = [
        'ORDER ID',
        'TYPE',
        'ORDERED DATE',
        'CUSTOMER NAME',
        'CUSTOMER CONTACT',
        'PICKED AT',
        'PICK DRIVER',
        'DELIVERED At',
        'DROP DRIVER',
        'MAIN AREA',
        'PAYMENT TYPE',
        'SERVICE TYPE',
        'TOTAL AMOUNT'
      ];

      if(!count($data)){
        return back()->with('error','Empty Data');
      }

      return Excel::download(new Report($data,$head), $fileName.'.xlsx');
    }
}
