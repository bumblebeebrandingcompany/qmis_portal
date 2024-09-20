@extends('layouts.client')

@section('content')
    <div class="screen-1">
        <div class="rectangle-1">
            <div class="button-container" style="margin-top: 90px;">
                <a href="{{ route('client.enquiryForm') }}" class="sign-up-text">Sign Up Now</a>
            </div>

        </div>
    </div>
@endsection

<style>
    .rectangle-1 {
        background: url('../assets/images/welcome_screen.svg') no-repeat center center/cover;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        height: 100%;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .button-container {
        position: fixed; /* Changed to fixed */
        z-index: 2;
        text-align: center;
        top: 50%; /* Adjust this value to fine-tune vertical positioning */
        left: 50%; /* Center horizontally */
        transform: translate(-50%, -50%); /* Center the button in both directions */
    }

    .sign-up-text {
        font-size: 26px;
        font-family: 'Nunito Sans', sans-serif;
        color: #D21B21;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .sign-up-text:hover {
        color: #B0171F;
    }
</style>
