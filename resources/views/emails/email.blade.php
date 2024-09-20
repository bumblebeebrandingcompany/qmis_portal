<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal Access</title>
</head>
<body>
    <h1>Client Portal Access</h1>
    <p>Dear {{ $lead->father_name ?? $lead->mother_name }},</p>
    <p>Click the link below to access your client portal:</p>
    <p><a href="{{ route('client.login', ['token' => $lead->login_token]) }}">Access Client Portal</a></p>
    <p>Thank you!</p>
</body>
</html>
