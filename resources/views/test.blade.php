<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="{{route('createPayment')}}" method="post">
		@csrf
		<input type="number" name="order_id">
		<input type="submit" value="PAYPAL PAYMENT">
	</form>
</body>
</html>