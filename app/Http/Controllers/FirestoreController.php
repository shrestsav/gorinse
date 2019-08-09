<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;

class FirestoreController extends Controller
{
	protected $db;
	protected $name;

	public function __construct()
	{
		$this->db = new FirestoreClient([
	        'projectId' => env('GOOGLE_CLOUD_PROJECT'),
	    ]);
	}

    public function index()
    {
		$collectionReference = $this->db->collection('orders');
		$documentReference = $collectionReference->document('jyecqfszbzl39k0yz6g');
		$snapshot = $documentReference->snapshot();

		return $snapshot->data();
    }
    public function setData()
    {
		// $ordersRef = $this->db->collection('orders')->newDocument();
		// $ordersRef->set([
		//     'customer_id' => '3',
		//     'order_date' => '2019-02-03',
		//     'type' => 'normal',
		//     'pickup_place' => 'kathmandu',
		//     'pickup_time' => 'kathmandu',
		//     'dropoff_place' => 'kathmandu',
		//     'dropoff_time' => 'kathmandu',
		//     'total_amount' => 'kathmandu',
		//     'vat_amount' => 'kathmandu',
		//     'delivery_charge' => 'kathmandu',
		//     'status' => 'kathmandu',
		// ]);		

		$usersRef = $this->db->collection('users')->newDocument()->collection('orders')->newDocument();
		$usersRef->set([
		    'username' => $this->getName(5),
		    'fname' => $this->getName(6),
		    'lname' => $this->getName(4),
		    'registered_on' => 'kathmandu',
		    'address' => 'fasdfsdf',
		]);
		return $usersRef->id();
    }

    public function getName($n) { 
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	    $randomString = ''; 
	  
	    for ($i = 0; $i < $n; $i++) { 
	        $index = rand(0, strlen($characters) - 1); 
	        $randomString .= $characters[$index]; 
	    } 
	  
	    return $randomString; 
	}

    public function whereData()
    {
    	$citiesRef = $this->db->collection('cities')->document('SF');
    	$snapshot = $citiesRef->snapshot();
    	if ($snapshot->exists()) {
		    printf('Document data:' . PHP_EOL);
		    print_r($snapshot->data());
		} else {
		    printf('Document %s does not exist!' . PHP_EOL, $snapshot->id());
		}

    }
}
