<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Screen</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <!-- SVG Image -->
        <img src="{{ asset('assets/vectors/pencil.svg') }}" alt="Pencil Image" class="pencil-image">

        <!-- Text Content -->
        <div class="text-content">
            <p style="color: #D21B21;font-size:20;font-weight:bold">Choosing the right school can feel overwhelming, but we’re here to make it easier.</p>
            <p style="color: #10194A;font-size:20;font-weight:bold">At QMIS, Safety isn’t just a priority, it’s a promise.</p>
        </div>

        <!-- Bullet Points -->
        <ul class="bullet-points">
            <li>Physical safety</li>
            <li>Mental Well-being</li>
            <li>Transport safety</li>
            <li>COVID-19 safety norms</li>
        </ul>

        <!-- Next Button -->
        <form id="redirectForm" action="{{ url('client/school', ['id' => $lead]) }}" method="GET">
            @csrf
            <button type="submit" class="next-button" style="background-color: #10194A;color:#ffffff">Next</button>
        </form>
    </div>
</body>

</html>

<style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #ffffff;
        font-family: Arial, sans-serif;
    }

    .container {
        text-align: center;
        max-width: 600px;
        padding: 20px;
    }

    .pencil-image {
        max-width: 150px;
        margin-bottom: 20px;
    }

    .text-content {
        margin-bottom: 20px;
    }

    .text-content p {
        font-size: 16px;
        color: #000;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .bullet-points {
        list-style-type: disc;
        margin: 0;
        padding: 0;
        text-align: left;
        display: inline-block;
        max-width: 100%;
        margin-bottom: 20px;
    }

    .bullet-points li {
        font-size: 16px;
        color: #000;
        margin-bottom: 10px;
    }

    .next-button {
        font-size: 16px;
        color: white;
        background-color: #6a008a;
        /* Customize the button color */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    .next-button:hover {
        background-color: #53006b;
        /* Darker shade for hover effect */
    }
</style>
