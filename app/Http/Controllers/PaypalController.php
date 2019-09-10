<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

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
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$itemsArr = [];
		$total_amount = 0;
		foreach($orderDetails['items_details'] as $itemDetail){
			$item = new Item();
			$item->setName($itemDetail['item'])
				 ->setCurrency('USD')
				 ->setQuantity($itemDetail['quantity'])
				 ->setSku('ITM#'.$itemDetail['item_id']) // Similar to `item_number` in Classic API
				 ->setPrice($this->convertCurrency($itemDetail['price']));
			array_push($itemsArr,$item);
			$total_amount+=$itemDetail['quantity']*$this->convertCurrency($itemDetail['price']);
		}

		$itemList = new ItemList();
		$itemList->setItems($itemsArr);

		$details = new Details();
		$details->setShipping($this->convertCurrency($orderDetails['invoice_details']['delivery_charge']))
		    	->setTax($this->convertCurrency($orderDetails['invoice_details']['VAT']))
		    	->setSubtotal($total_amount);

		$grand_total = $this->convertCurrency($orderDetails['invoice_details']['delivery_charge'])+$this->convertCurrency($orderDetails['invoice_details']['VAT'])+$total_amount;

		$amount = new Amount();
		$amount->setCurrency("USD")
			   ->setTotal($grand_total)
			   ->setDetails($details);
		// return $itemList.'<br>'.$details.'<br>'.$amount.'<br>'.$orderDetails;

		$transaction = new Transaction();
		$transaction->setAmount($amount)
		    		->setItemList($itemList)
		    		->setDescription("Payment description")
		    		->setInvoiceNumber('GORINSE#'.$request->order_id.'-'.uniqid());

		$baseUrl = url('');
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($baseUrl."/paypal/execute/".$request->order_id)
		    		 ->setCancelUrl($baseUrl."/paypal/cancel"); //Not Integrated

		$payment = new Payment();
		$payment->setIntent("sale")
		    	->setPayer($payer)
		    	->setRedirectUrls($redirectUrls)
		    	->setTransactions(array($transaction));

		$request = clone $payment;

		try {
		    $payment->create($this->apiContext);
		} catch (Exception $ex) {
			// return ("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
			return response()->json([
                'status' => '400',
                'message' => $ex->getMessage(),
                'exceptions' => $ex,
            ], 400);
		}
		$approvalUrl = $payment->getApprovalLink();

		return response()->json([
                'status' => '201',
                'message' => 'Paypal Approval Link Created, proceed with the approvalUrl to start the payment',
                'approvalUrl' => $approvalUrl,
            ], 201);

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

	    $total_amount = 0;
		foreach($orderDetails['items_details'] as $itemDetail){
			$total_amount+=$itemDetail['quantity']*$this->convertCurrency($itemDetail['price']);
		}

	    $details->setShipping($this->convertCurrency($orderDetails['invoice_details']['delivery_charge']))
		    	->setTax($this->convertCurrency($orderDetails['invoice_details']['VAT']))
		    	->setSubtotal($total_amount);

		$grand_total = $this->convertCurrency($orderDetails['invoice_details']['delivery_charge'])+$this->convertCurrency($orderDetails['invoice_details']['VAT'])+$total_amount;

		$amount->setCurrency("USD")
			   ->setTotal($grand_total)
			   ->setDetails($details);

	    $transaction->setAmount($amount);

	    $execution->addTransaction($transaction);

	    $result = $payment->execute($execution, $this->apiContext);

	    $transactions = $result->getTransactions();

	    //Update payment status for order
	    $order = Order::findOrFail($order_id)->update(['payment' => 1]);
	    $orderDetails = OrderDetail::updateOrCreate(
                ['order_id' => $order_id],
                [
                	'payment_id' => $result->getId(),
                	'PT' => Date('Y-m-d h:i:s')
                ]
            );
	    
	    return response()->json([
                'status' => '200',
                'message' => 'Payment Successful',
                'invoice_id' => $transactions[0]->invoice_number,
                'payment_id' => $result->getId(),
            ], 200);
    }

    public function convertCurrency($AED)
    {
    	$AED_TO_USD = number_format(1/config('settings.USD_TO_AED'),100);
    	return number_format($AED*$AED_TO_USD,2);
    }

    public function retrievePayment($paymentID)
    {
    	try {
		    $payment = Payment::get($paymentID, $this->apiContext);
		} catch (Exception $ex) {
			return 'error';
		}
    	return response()->json(json_decode($payment));
    }
}
