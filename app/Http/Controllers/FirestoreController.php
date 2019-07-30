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
		$ordersRef = $this->db->collection('orders')->newDocument();
		$ordersRef->set([
		    'customer_id' => '3',
		    'order_date' => '2019-02-03',
		    'type' => 'normal',
		    'pickup_place' => 'kathmandu',
		    'pickup_time' => 'kathmandu',
		    'dropoff_place' => 'kathmandu',
		    'dropoff_time' => 'kathmandu',
		    'total_amount' => 'kathmandu',
		]);		

		$usersRef = $this->db->collection('users')->newDocument();
		$usersRef->set([
		    'username' => 'Utsav',
		    'type' => 'normal',
		    'f_name' => 'kathmandu',
		    'l_name' => 'kathmandu',
		    'last_login' => 'kathmandu',
		    'registered_on' => 'kathmandu',
		    'total_amount' => 'kathmandu',
		]);
		return $ordersRef->id();
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
