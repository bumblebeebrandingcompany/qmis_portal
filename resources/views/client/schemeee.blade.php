@extends('layouts.client')
@section('content')
    <style>
        .screen-9 {
            position: relative;
            width: 100%;
            height: 100vh;
            background: #f0f0f0;
            overflow: auto;
        }

        .container-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -7.5px;
            margin-left: -7.5px
        }

        .col,
        .col-1,
        .col-10,
        .col-11,
        .col-12,
        .col-2,
        .col-3,
        .col-4,
        .col-5,
        .col-6,
        .col-7,
        .col-8,
        .col-9,
        .col-auto,
        .col-lg,
        .col-lg-1,
        .col-lg-10,
        .col-lg-11,
        .col-lg-12,
        .col-lg-2,
        .col-lg-3,
        .col-lg-4,
        .col-lg-5,
        .col-lg-6,
        .col-lg-7,
        .col-lg-8,
        .col-lg-9,
        .col-lg-auto,
        .col-md,
        .col-md-1,
        .col-md-10,
        .col-md-11,
        .col-md-12,
        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-5,
        .col-md-6,
        .col-md-7,
        .col-md-8,
        .col-md-9,
        .col-md-auto,
        .col-sm,
        .col-sm-1,
        .col-sm-10,
        .col-sm-11,
        .col-sm-12,
        .col-sm-2,
        .col-sm-3,
        .col-sm-4,
        .col-sm-5,
        .col-sm-6,
        .col-sm-7,
        .col-sm-8,
        .col-sm-9,
        .col-sm-auto,
        .col-xl,
        .col-xl-1,
        .col-xl-10,
        .col-xl-11,
        .col-xl-12,
        .col-xl-2,
        .col-xl-3,
        .col-xl-4,
        .col-xl-5,
        .col-xl-6,
        .col-xl-7,
        .col-xl-8,
        .col-xl-9,
        .col-xl-auto {
            position: relative;
            width: 100%;
            min-height: 1px;
            padding-right: 7.5px;
            padding-left: 7.5px
        }

        .col {
            flex-basis: 0;
            flex-grow: 1;
            max-width: 100%
        }

        .col-auto {
            flex: 0 0 auto;
            width: auto;
            max-width: none
        }

        .col-1 {
            flex: 0 0 8.333333%;
            max-width: 8.333333%
        }

        .col-2 {
            flex: 0 0 16.666667%;
            max-width: 16.666667%
        }

        .col-3 {
            flex: 0 0 25%;
            max-width: 25%
        }

        .col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%
        }

        .col-5 {
            flex: 0 0 41.666667%;
            max-width: 41.666667%
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%
        }

        .col-7 {
            flex: 0 0 58.333333%;
            max-width: 58.333333%
        }

        .col-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%
        }

        .col-9 {
            flex: 0 0 75%;
            max-width: 75%
        }

        .col-10 {
            flex: 0 0 83.333333%;
            max-width: 83.333333%
        }

        .col-11 {
            flex: 0 0 91.666667%;
            max-width: 91.666667%
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%
        }

        .container-5 {
            display: flex;
            gap: 40px;
            align-items: center;
            flex-grow: 1;
            justify-content: flex-end;
        }

        .screen-9 .elmgeneralindicatoractivesection {
            border-radius: 0.1rem;
            background: #F1416C;
            margin-right: 1.4rem;
            width: 0.3rem;
            height: 3.3rem;
            padding: 0%
        }


        .avatar {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            background-color: rgba(210, 27, 33, 0.23);
            color: #F1416C;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-family: 'Inter'
        }


        .container-2 {
            padding-top: 5%;
            padding-bottom: 5%;
            padding-left: 0%;
            padding-right: 5%;
            margin: 5%;
            background: #ffffff;
            border-radius: 16px;

        }

        /* Time text style */
        .time {
            font-size: 18px;
            font-weight: bold;
        }

        /* Adjust the icon size inside the container */
        .container-6 img {
            width: 24px;
            height: 24px;
            margin-left: 10px;
        }

        /* Group container with flexible layout */
        .group-8931 {
            display: flex;
            align-items: center;
        }

        .text-button {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .arrow {
            width: 16px;
            height: 16px;
            margin-right: 10px;
        }

        .back-to-info-portal {
            font-size: 16px;
            font-weight: bold;
        }

        /* Time tracker title */
        .time-tracker-perso {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            margin-left: 20px;
        }

        /* Project container with pointer cursor */
        .project {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            cursor: pointer;
        }

        /* Flex container for the project section */
        .container {
            display: flex;
            align-items: center;
            flex: 1;
            cursor: pointer;
        }

        /* Technical task title */
        .technical-task {
            font-size: 18px;
            font-weight: bold;
            margin-left: 10px;
        }

        /* Technical task heading */
        .technical-task-1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Card-like container with shadow */
        .container-4 {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Requirements text containers */
        .requirements-for-web,
        .when-developing-the,
        .requirements-for-sit,
        .requirements-for-the,
        .all-published-sectio {
            margin-bottom: 10px;
        }

        /* Expandable content hidden by default */
        .expandable-content {
            display: none;
            padding: 10px;
            border-top: 1px solid #ddd;
            background: #f9f9f9;
        }

        /* Active state for expandable content */
        .expandable-content.active {
            display: block;
        }

        /* Button styling */
        .button {
            background-color: #6a008a;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
        }

        /* Button hover effect */
        .button:hover {
            background-color: #5a007a;
        }

        /* Fixed button container */
        .fixed-button-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            justify-content: center;
        }

        /* General icon size */
        .icngeneralsearch img,
        .icngeneralnotifications img {
            width: 24px;
            height: 24px;
        }

        /* Resized image styling */
        .resized-image {
            height: 60px;
            width: 60px;
        }
    </style>

    <div class="screen-9">
        <div class="container-2">
            <div class="row">
                <div class="col-5">
                    <img class="group resized-image" src="{{ asset('assets/vectors/group_3_x2.svg') }}" alt="Group" />
                </div>
                <div class="col-6 container-5">
                    <div class="icngeneralsearch">
                        <img class="search" src="{{ asset('assets/vectors/search_5_x2.svg') }}" alt="Search" />
                    </div>
                    <div class="icngeneralnotifications">
                        <img class="notifications" src="{{ asset('assets/vectors/notifications_5_x2.svg') }}"
                            alt="Notifications" />
                    </div>
                    <div class="avatar">
                        <span class="aa">S</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="time-tracker-perso">QMIS Student<br />Schemes</div>

        <div class="container-2">
            <div class="project" id="toggle-menu">
                <div class="container">
                    <div class="elmgeneralindicatoractivesection"></div>
                    <img class="vector-15" src="{{ asset('assets/vectors/vector_2_x2.svg') }}" alt="Vector" />
                    <div class="technical-task">Schemes</div>
                </div>
                <div class="icngeneralarrowdarkright">
                    <img class="arrow-1" src="{{ asset('assets/vectors/arrow_51_x2.svg') }}" alt="Arrow Right" />
                </div>

            </div>

            <div class="expandable-content" id="expandable-content">
                <div class="technical-task-1">Schemes details</div>
                <div class="container-2">

                    <div class="requirements-for-web">Lorem ipsum dolor sit amet</div>
                    <div class="when-developing-the">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                        commodo justo eget consectetur efficitur. Nullam massa est, scelerisque at enim at, sodales
                        volutpat dolor. In ut odio quis odio maximus luctus<br /><br />Lorem ipsum dolor sit amet,
                        consectetur adipiscing elit.</div>
                    <div class="requirements-for-sit">Lorem ipsum dolor<br />sit amet</div>
                    <div class="requirements-for-the">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                        commodo justo eget consectetur efficitur. Nullam massa est, scelerisque at enim at, sodales
                        volutpat dolor. In ut odio quis odio maximus luctus<br /><br />Lorem ipsum dolor sit amet,
                        consectetur adipiscing elit.</div>
                    <span class="all-published-sectio">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                        commodo justo eget consectetur efficitur. Nullam massa est, scelerisque at enim at, sodales
                        volutpat dolor. In ut odio quis odio maximus luctus<br /><br />Lorem ipsum dolor sit amet, enim
                        at, consectetur adipiscing elit. at sodales volutpat dolor. In ut odio quis odio maximus
                        luctus</span>
                </div>
                <span class="access-sharing-requi">Lorem ipsum dolor sit amet</span>
            </div>
        </div>
    </div>
    </div>
    <div class="fixed-button-container">
        <div class="button">Schedule school visit</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleMenu = document.getElementById('toggle-menu');
            const expandableContent = document.getElementById('expandable-content');

            toggleMenu.addEventListener('click', function() {
                expandableContent.classList.toggle('active');
            });
        });
    </script>
@endsection
