<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: sans-serif; text-align:center; padding:40px;">

    @if ($status === 'success')
        <h1 style="color: green;">✅ Payment Successful</h1>
    @elseif ($status === 'pending')
        <h1 style="color: orange;">⏳ Payment Pending</h1>
    @else
        <h1 style="color: red;">❌ Payment Failed</h1>
    @endif

    <p>{{ $message }}</p>

    <p><strong>Transaction ID:</strong> {{ $transaction_id }}</p>

    <a href="/" style="display:inline-block;margin-top:20px;">Return Home</a>

</body>
</html>
