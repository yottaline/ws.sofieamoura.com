<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h1>Hello!</h1>

        <p>You have requested a password reset for your retailer account.</p>

        <p>Click the button below to reset your password:</p>

        <a href="{{ url('reset-password', $token) }}" class="btn btn-primary">Reset Password</a>

        {{-- <p>This link will expire in {{ config('auth.passwords.retailers.expire') }} minutes.</p> --}}

        <p>If you did not request a password reset, please ignore this email.</p>
    </div>

</body>

</html>
