@extends('layouts.client')

@section('content')
    <div class="screen-1">
        <div class="rectangle-1"></div>
        <div class="rectangle-2"></div>
        <div class="rectangle-3"></div>
        <div class="container">
            <div class="welcome-to">WELCOME TO</div>
            <img class="qmis-logo-white" src="{{ asset('assets/vectors/qmis_logo_white_2_x2.svg') }}" />
            <div class="align-items-center text-center" style="color: #ffffff;font-size:12px;margin:5%;font-family:'Nunito sans'">
                As your child takes the first step into schooling, we set the stage for their bright future!
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Wait for the DOM to fully load
    document.addEventListener("DOMContentLoaded", function() {
        // Set a timeout for 2 seconds (2000 milliseconds)
        setTimeout(function() {
            // Redirect to the specified URL
            window.location.href = "{{ url('/client/welcome') }}";
        }, 2000); // 2-second delay
    });
</script>
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
    }

    .welcome-to {
        margin-bottom: 1.3rem;
        display: inline-block;
        overflow-wrap: break-word;
        font-family: 'Nunito Sans';
        font-weight: bold;
        font-size: 1.8rem;
        line-height: 1;
        text-transform: uppercase;
        color: #D21B21;
        z-index: 6;
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
        box-sizing: border-box;
    }
</style>
