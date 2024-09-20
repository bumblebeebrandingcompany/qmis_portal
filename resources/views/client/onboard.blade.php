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
        <img src="{{ asset('assets/vectors/handshake.svg') }}" alt="Onboard Image" class="onboard-image">

        <!-- Heading -->
        <h1>Excited to have you onboard!</h1>

        <!-- Paragraph Text -->
        <p>We understand that sending your child to school for the first time brings a wave of emotions—don’t worry,
            we’ve got you covered every step of the way.</p>
        <h3 class="color_bold">Your enquiry no. is {{@$lead->ref_num}}</h3>

        <p class="">You are one step away from submitting the application form.
        </p>
        <!-- Welcome Text -->
        <p>We're thrilled to welcome you to the Queen Mira family!</p>

        <!-- Next Button -->
        <form id="redirectForm" action="{{ url('client/academic', ['id' => $lead]) }}" method="GET">
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
        /* Background color white */
        font-family: Arial, sans-serif;
    }

    .container {
        text-align: center;
        max-width: 600px;
        padding: 20px;
    }

    .onboard-image {
        max-width: 150px;
        /* Adjust the size of the SVG image */
        margin-bottom: 20px;
    }

    .color_bold {
        color: #D21B21;
        font-weight:bold;
    }

    h1 {
        font-size: 20px;
        color: #D21B21;
        margin-bottom: 20px;

    }

    p {
        font-size: 16px;
        color: #10194A;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .next-button {
        font-size: 16px;
        color: white;
        background-color: #6a008a; /* Customize the button color */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    .next-button:hover {
        background-color: #53006b; /* Darker shade for hover effect */
    }
</style>
