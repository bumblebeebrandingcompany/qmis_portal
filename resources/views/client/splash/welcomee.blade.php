@extends('layouts.client')

@section('content')
    <div class="screen-2">
        <div class="rectangle-1"></div>
        <div class="rectangle-2"></div>
        <div class="rectangle-3"></div>
        <div class="container">
            <img class="qmis-logo-white" src="{{ asset('/assets/vectors/qmis_logo_white_x2.svg') }}" />
            <div class="world-class-facilitiesand-infrastructure">
                World-class Facilities<br />
                and Infrastructure
            </div>
            <div class="line-1">
            </div>
            <div class="offer-1">
                Our campus has been crafted as a nurturing educational setting to foster the intellectual, physical, social,
                and emotional growth of our students. Here are some of the features our campus proudly offers:
            </div>
            <div class="offer-2">
                Our campus has been crafted as a nurturing educational setting to foster the intellectual, physical, social,
                and emotional growth of our students. Here are some of the features our campus proudly offers:
            </div>
            <button class="main-button">
                <span class="lets-start">Let&#39;s Start</span>
                <div class="icngeneralarrowgowhite">
                    <img class="arrow" src="{{ asset('/assets/vectors/arrow_5_x2.svg') }}" />
                </div>
            </button>
        </div>
    </div>
    <link href="https://fonts.googleapis.com/css2?family=Astro:wght@300&display=swap" rel="stylesheet">
@endsection
<style>
    .rectangle-1 {
        background: url('../assets/images/rectangle_11.png') no-repeat center center/cover;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        height: 100%;
        z-index: 1;
    }

    .rectangle-2 {
        background: rgba(16, 25, 74, 0.5)80%;
        background-blend-mode: color;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        height: 100%;
        z-index: 3;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    }

    .rectangle-3 {
        background: linear-gradient(180deg, rgba(16, 25, 74, 1) 0%, rgba(255, 255, 255, 0.1)100%);
        background-blend-mode: multiply;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        height: 60%;
        z-index: 2;
        /* Ensure this is above rectangle-2 but below content */
    }


    .qmis-logo-white {
        margin: 0 0 1.6rem 0.6rem;
        width: 10.4rem;
        height: 5.1rem;
    }

    .container {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 11.1rem 0 30rem 0;
        width: 100%;
        height: fit-content;
        z-index: 5;
        /* Ensure it's above rectangle-1, rectangle-2, and rectangle-3 */
        box-sizing: border-box;
    }

    .screen-2 .offer-1 {
        margin: 0 0 0.8rem 0.1rem;
        display: inline-block;
        text-align: center;
        overflow-wrap: break-word;
        font-family: 'Astro';
        font-weight: 300;
        font-size: 10px;
        text-transform: capitalize;
        color: #FFFFFF;
    }

    .screen-2 .offer-2 {
        margin: 0 0 2.1rem 0.1rem;
        display: inline-block;
        text-align: center;
        overflow-wrap: break-word;
        font-family: 'Astro', 'Roboto Condensed';
        font-weight: 300;
        font-size: 0.6rem;
        line-height: 1.2;
        text-transform: capitalize;
        color: #FFFFFF;
    }

    .world-class-facilitiesand-infrastructure {
        margin-bottom: 0.9rem;
        display: inline-block;
        text-align: center;
        overflow-wrap: break-word;
        font-family: 'Astro', 'Roboto Condensed';
        font-weight: 500;
        font-size: 1.5rem;
        line-height: 1.083;
        text-transform: uppercase;
        color: #F5F5F5;
    }

    .main-button {
        box-shadow: #D21B21;
        border-radius: 0.3rem;
        background: #D21B21;
        position: relative;
        margin-left: 0.1rem;
        display: flex;
        /* flex-direction: row; */
        padding: 0.8rem 1.3rem;
        width: fit-content;
    }

    .lets-start {
        margin-right: 0.7rem;
        overflow-wrap: break-word;
        font-family: 'Nunito Sans';
        font-weight: 700;
        font-size: 1rem;
        color: #FFFFFF;
    }

    .screen-2 .arrow {
        width: 1rem;
        height: 0.8rem;
    }

    .screen-2 .icngeneralarrowgowhite {
        margin: 0.3rem 0;
        display: flex;

    }
</style>
