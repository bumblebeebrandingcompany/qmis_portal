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
    <title>Application Status</title>
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
            <h3>Welcome back, Sriram</h3>
            <h2>Application Status</h2>
            <div class="status-card">
                <div class="card-header">
                    <img src="{{ asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                    <div class="user-info">
                        <h4>Sriram Sam</h4>
                        <p>PN0001265</p>
                        <p>Father</p>
                    </div>
                    <div class="options">
                        <i class="fas fa-ellipsis-h"></i> <!-- Horizontal Ellipsis Icon -->
