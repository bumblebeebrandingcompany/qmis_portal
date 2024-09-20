<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal Access</title>
</head>
<body>
    <h1>Client Portal Access</h1>
    <p>Dear {{ $lead->father_name ?? $lead->mother_name ?? $lead->guardian_name }},</p>
    <p>Your one-time password (OTP) is: <strong>{{ $otp }}</strong></p>
    <p>Please enter this code to complete your submission and access your client portal.</p>
    <p>Thank you!</p>
    <footer>
        <p>Powered by The Bumblebee Branding Company</p>
    </footer>
</body>
</html>
