<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Process</title>
    <style>
        body {
            background-color: #000B6B;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .card h1 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .card img {
            width: 100px;
            margin: 20px 0;
        }

        .card p {
            font-size: 1em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Application Under Review</h1>

        <img src="{{ asset('images/application.svg') }}" alt="Application">

        <p>Thank you. You have successfully paid the application fee.</p>
    </div>
</body>
</html>
