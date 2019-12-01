@component('mail::message')
Dear {{$mailData['name']}},

{{$mailData['message']}}<br>

# Order Details
Order No: #{{$mailData['orderID']}}
@php $i=0; @endphp
@component('mail::table')
| SNO       | ITEMS         | QUANTITY  | AMOUNT  |
| ------------- |:-------------:| --------:| --------:|
@foreach($mailData['orderDetails']['items_details'] as $item)
| {{++$i}}      | {{$item['item']}}<br>({{$item['remarks']}})      | {{$item['quantity']}}      | AED {{$item['total']}}      |
@endforeach

#### ORDER REMARK 	: {{$mailData['orderDetails']['invoice_details']['PDR']}}
#### TOTAL AMOUNT 	: AED {{$mailData['orderDetails']['invoice_details']['total_amount']}}
#### VAT 		   	: AED {{$mailData['orderDetails']['invoice_details']['VAT']}}
#### DELIVERY CHARGE : AED {{$mailData['orderDetails']['invoice_details']['delivery_charge']}}
#### GRAND TOTAL 	: AED {{$mailData['orderDetails']['invoice_details']['grand_total']}}

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent