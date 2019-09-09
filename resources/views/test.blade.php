<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="{{route('createPayment')}}" method="post">
		@csrf
		<input type="hidden" name="order_id" value="18">
		<input type="submit" value="PAYPAL PAYMENT">
	</form>
</body>
</html>