@extends('layouts.client')

@section('content')
    <div class="screen-1">
        <div class="rectangle-1">
            <form id="redirectForm" action="{{ url('client/application', ['id' => $lead]) }}" method="GET">
                @csrf
                <button type="submit" class="next-button">Let's Start</button>
            </form>
        </div>
    </div>
@endsection

<style>
    .rectangle-1 {
        background: url('{{ asset('assets/images/one-step.svg') }}');
        background-size: cover;
        /* Ensure the image covers the background */
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
        flex-direction: column;
        /* Allow for vertical alignment of children */
        position: relative;
        /* Ensure child elements are positioned relative to this container */
    }

    #redirectForm {
        position: absolute;
        /* Remove from normal document flow */
        bottom: 20px;
        /* Distance from the bottom of the container */
        left: 50%;
        /* Center horizontally */
        transform: translateX(-50%);
        /* Center horizontally based on its width */
        width: auto;
        /* Adjust width to fit content */
        max-width: 90%;
        /* Ensure the form doesn't stretch too wide */
    }

    .next-button {
        background-color: #D21B21;
        /* Button color */
        color: white;
        /* Button text color */
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin: 0 20px;
        margin-bottom: 150%
        /* Add left and right margin */
    }

    .next-button:hover {
        background-color: #0a123e;
        /* Darker shade for hover effect */
    }
</style>
