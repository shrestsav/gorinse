<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Illuminate\Routing\UrlGenerator;

class PaypalController extends Controller
{
	protected $apiContext;

	public function __construct()
	{
		$this->apiContext = new \PayPal\Rest\ApiContext(
		        new \PayPal\Auth\OAuthTokenCredential(
		            config('services.paypal.id'),     // ClientID
		            config('services.paypal.secret')  // ClientSecret
		        )
		);
	}
	public function createPayment(Request $request)
	{
		$orderDetails = Order::findOrFail($request->order_id)->generateInvoiceForUser();
		// return $orderDetails;
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$itemsArr = [];
		foreach($orderDetails['items_details'] as $itemDetail){
			$item = new Item();
			$item->setName($itemDetail['item'])
				 ->setCurrency('USD')
				 ->setQuantity($itemDetail['quantity'])
				 ->setSku(rand(1000,9999)) // Similar to `item_number` in Classic API
				 ->setPrice($itemDetail['price']);
			array_push($itemsArr,$item);
		}
		// $item1 = new Item();
		// $item1->setName('Ground Coffee 40 oz')
		// 	  ->setCurrency('USD')
		// 	  ->setQuantity(1)
		// 	  ->setSku("123123") // Similar to `item_number` in Classic API
		// 	  ->setPrice(7.5);
		
		// $item2 = new Item();
		// $item2->setName('Granola bars')
		//       ->setCurrency('USD')
		//       ->setQuantity(5)
		//       ->setSku("321321") // Similar to `item_number` in Classic API
		//       ->setPrice(2);

		$itemList = new ItemList();
		// $itemList->setItems(array($item1, $item2));
		$itemList->setItems($itemsArr);
		// return $itemList;
		$details = new Details();
		$details->setShipping($orderDetails['invoice_details']['delivery_charge'])
		    	->setTax($orderDetails['invoice_details']['VAT'])
		    	->setSubtotal($orderDetails['invoice_details']['total_amount']);
		// return $details;
		$amount = new Amount();
		$amount->setCurrency("USD")
			   ->setTotal($orderDetails['invoice_details']['grand_total'])
			   ->setDetails($details);
		// return $amount;

		$transaction = new Transaction();
		$transaction->setAmount($amount)
		    		->setItemList($itemList)
		    		->setDescription("Payment description")
		    		->setInvoiceNumber(uniqid());

		$baseUrl = url('');
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($baseUrl."/paypal/execute/".$request->order_id)
		    		 ->setCancelUrl($baseUrl."/paypal/cancel"); //Not Integrated

		$payment = new Payment();
		$payment->setIntent("sale")
		    	->setPayer($payer)
		    	->setRedirectUrls($redirectUrls)
		    	->setTransactions(array($transaction));

		$payment->create($this->apiContext);

		$approvalUrl = $payment->getApprovalLink();

		return redirect($approvalUrl);
	}
    public function executePayment($order_id)
    {
    	$orderDetails = Order::findOrFail($order_id)->generateInvoiceForUser();
    	// After Step 1
		$paymentId = request('paymentId');
    	$payment = Payment::get($paymentId, $this->apiContext);

    	$execution = new PaymentExecution();
    	$execution->setPayerId(request('PayerID'));

    	$transaction = new Transaction();
	    $amount = new Amount();
	    $details = new Details();

	    $details->setShipping($orderDetails['invoice_details']['delivery_charge'])
		    	->setTax($orderDetails['invoice_details']['VAT'])
		    	->setSubtotal($orderDetails['invoice_details']['total_amount']);

		$amount->setCurrency('USD');
	    $amount->setTotal($orderDetails['invoice_details']['grand_total']);
	    $amount->setDetails($details);
	    $transaction->setAmount($amount);

	    $execution->addTransaction($transaction);

	    $result = $payment->execute($execution, $this->apiContext);

    	return $result;
    }
}
