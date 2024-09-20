<!DOCTYPE html>
<html lang="en">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo">
            </div>
            <div class="icons">
                <a href="#"><img src="{{ asset('icons/search.ico') }}" alt="Search"></a>
                <a href="#"><img src="{{ asset('icons/notifications.ico') }}" alt="Notifications"></a>
                <a href="#"><img src="{{ asset('images/profile-icon.png') }}" alt="Profile"></a>
            </div>
        </header>

        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/group.jpg') }}" class="d-block w-100" alt="...">
                    <div class="banner-text">
                        <h6>Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit.</h6>
                        <button>Schedule School Visit</button>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/group.jpg') }}" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/group.jpg') }}" class="d-block w-100" alt="...">
                </div>
            </div>
            <!-- Optional controls -->
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
        <section class="status-section">
            <h2>Resources</h2>
            <div class="card">
                <div class="card-header">
                    <img src="{{ asset('icons/folder1.ico') }}" alt="Logo" class="small-icon">
                </div>

                <div class="card-body">
                    <p>Schedule</p>
                    <button class="btn btn-primary"
                        style="background-color: #00D097; border-color: #00D097;">Download</button>
                </div>

            </div>
        </section>
        <section class="status-section">
            <div class="card">
                <div class="card-header">
                    <img src="{{ asset('icons/folder.ico') }}" alt="Logo" class="small-icon">
                </div>

                <div class="card-body">
                    <p>Fees structure</p>
                    <button class="btn btn-primary"
                        style="background-color: #00D097; border-color: #00D097;">Download</button>

                </div>


            </div>
        </section>
        <section class="status-section">
            <div class="card">
                <div class="card-header">
                    <img src="{{ asset('icons/folder2.ico') }}" alt="Logo" class="small-icon">
                </div>

                <div class="card-body">
                    <p>Offers</p>
                    <button class="btn btn-primary"
                        style="background-color: #00D097; border-color: #00D097;">Download</button>
                </div>
            </div>
        </section>

    </div>
</body>

</html>
<style>
    /* Bootstrap Dots */
    .carousel-indicators li {
        background-color: #bbb;
    }

    .carousel-indicators .active {
        background-color: #333;
    }

    /* Slick Dots */
    .slick-dots li button:before {
        color: #bbb;
        /* Inactive dot color */
    }

    .slick-dots li.slick-active button:before {
        color: #333;
        /* Active dot color */
    }



    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        padding: 16px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
    }

    .header .logo img {
        height: 40px;
    }

    .header .icons img {
        width: 24px;
        margin-left: 16px;
    }

    .banner {
        position: relative;
    }

    .banner img {
        width: 100%;
        border-radius: 8px;
    }

    .banner-text {
        position: absolute;
        bottom: 16px;
        left: 16px;
        color: white;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
    }

    .banner-text h2 {
        margin: 0;
        font-size: 16px;
        font-weight: normal;
    }

    .banner-text button {
        background-color: #e74c3c;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        margin-top: 8px;
    }

    .status-section {
        margin-top: 24px;
    }

    .status-section h3 {
        font-size: 16px;
        color: #7f8c8d;
    }

    .status-section h2 {
        font-size: 20px;
        margin-bottom: 16px;
    }

    .card {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, .15);
    }

    .card-header {
        background-color: #f8f9fc;
        color: white;
        padding: 1rem 1.25rem;
    }

    .card-body {
        padding: 1.25rem;
    }

    .card-footer {
        background-color: #f8f9fc;
        padding: 1rem 1.25rem;
        border-top: 1px solid #e3e6f0;
    }

    .btn-primary {
        background-color: #e3e6f0;
        border-color: #e3e6f0;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #e3e6f0;
        border-color: #e3e6f0;
    }

    .small-icon {
        width: 30px;
        height: 30px;
    }
</style>
