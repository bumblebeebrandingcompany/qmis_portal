<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queen Mira Special</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <!-- SVG Image -->
        <img src="{{ asset('assets/vectors/boy.svg') }}" alt="Boy Image" class="boy-image">

        <!-- Heading -->
        <h1>What Makes QMIS Special? </h1>

        <!-- Paragraph Text -->
        <p class="description">Queen Mira's holistic approach to education helps your child excel academically, emotionally, socially, and creatively.</p>

        <!-- Subheading -->
        <p class="subheading">Here’s what your child will gain:</p>

        <!-- Bullet Points -->
        <ul class="bullet-points">
            <li>Strong academic foundation</li>
            <li>Creative thinking skills</li>
            <li>Emotional intelligence</li>
        </ul>

        <!-- Additional Text -->
        <p class="additional-text">Explore our KG programs below to see how we make learning magical.</p>
        <p class="additional-text">
            Click to explore our KG programmes in detail.</p>

        <!-- Click to Explore -->
        <form id="redirectForm" action="{{ url('client/application', ['id' => $lead]) }}" method="GET">
            @csrf
            <button type="submit" class="explore-button" style="background-color: #10194A;color:#ffffff">Next</button>
        </form>    </div>
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

    .boy-image {
        max-width: 150px;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 20px;
        font-weight: bold;
        color: #D21B21;
        margin-bottom: 20px;
    }

    .description {
        font-size: 12px;
        color: #10194A;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .subheading {
        font-size: 12px;
        color: #10194A;
        margin-bottom: 10px;
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
        font-size: 12px;
        color: #000;
        margin-bottom: 10px;
    }

    .additional-text {
        font-size: 12px;
        color: #000;
        margin-bottom: 20px;
    }

    .explore-button {
        font-size: 12px;
        color: #000;
        background-color: #fff;
        border: 1px solid #000;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    .explore-button:hover {
        background-color: #000;
        color: #fff;
    }
</style>
