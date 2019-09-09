<?php

namespace App\Paypal;

class CreatePayment
{

	public function create()
	{
		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		$item1 = new Item();
		$item1->setName('Ground Coffee 40 oz')
			  ->setCurrency('USD')
			  ->setQuantity(1)
			  ->setSku("123123") // Similar to `item_number` in Classic API
			  ->setPrice(7.5);
		$item2 = new Item();
		$item2->setName('Granola bars')
		      ->setCurrency('USD')
		      ->setQuantity(5)
		      ->setSku("321321") // Similar to `item_number` in Classic API
		      ->setPrice(2);

		$itemList = new ItemList();
		$itemList->setItems(array($item1, $item2));

		$details = new Details();
		$details->setShipping(1.2)
		    	->setTax(1.3)
		    	->setSubtotal(17.50);

		$amount = new Amount();
		$amount->setCurrency("USD")
			   ->setTotal(20)
			   ->setDetails($details);


		$transaction = new Transaction();
		$transaction->setAmount($amount)
		    		->setItemList($itemList)
		    		->setDescription("Payment description")
		    		->setInvoiceNumber(uniqid());

		// $baseUrl = getBaseUrl();
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl("http://localhost:8000/paypal/execute")
		    		 ->setCancelUrl("http://localhost:8000/paypal/cancel"); //Not Integrated

		$payment = new Payment();
		$payment->setIntent("sale")
		    	->setPayer($payer)
		    	->setRedirectUrls($redirectUrls)
		    	->setTransactions(array($transaction));

		$payment->create($apiContext);

		$approvalUrl = $payment->getApprovalLink();

		return redirect($approvalUrl);
	}
}