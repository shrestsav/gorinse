@if($mailData['emailType']==='new_order')
	
	@include('mail.__newOrder')

@elseif($mailData['emailType']==='new_registration')

	@include('mail.__newRegistration')

@elseif($mailData['emailType']==='invoice_confirmed')

	@include('mail.__shippedForLaundry')

@elseif($mailData['emailType']==='order_accepted')

	@include('mail.__orderAccepted')

@elseif($mailData['emailType']==='delivered_to_customer')

	@include('mail.__orderDelivered')

@endif