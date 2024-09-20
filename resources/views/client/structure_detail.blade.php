@extends('layouts.client')

@section('content')
    <div class="screen-1">
        <div class="scrollable-container">
            <div class="rectangle-1">
                <div style="width:100%">
                    @if($type == 'qmis')
                    <img style="width:100%" src="{{ asset('assets/images/det_eligibility.jpg') }}">
                    @else
                    <img style="width:100%" src="{{ asset('assets/images/det_eligibility.jpg') }}">
                    @endif
                </div>
                
            </div>
        </div>
    </div>
@endsection

<style>
    .scrollable-container {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow-y: auto;
    }

    .rectangle-1 {
        /* background: url('{{ asset('assets/images/det_eligibility.jpg') }}'); */
        background-size: cover;
        width: 100%;
        /* min-height: 200vh; */
        /* Set minimum height greater than viewport to trigger scrolling */
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        /* Flexbox alignment properties */
    }

    .content {
        padding: 20px;
        /* Add padding for content inside the rectangle-1 */
    }

    #redirectForm {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: auto;
        max-width: 90%;
    }

    .next-button {
        background-color: #D21B21;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin: 0 20px;
        margin-bottom: 150%;
    }

    .next-button:hover {
        background-color: #0a123e;
    }

    .bottom-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: white;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        padding: 20px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
        /* Center the button horizontally */
        align-items: center;
    }

    .schedule-button {
        background-color: #D21B21;
        /* Button background color */
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        font-family: "Roboto", sans-serif;
        transition: background-color 0.3s ease;
        /* Smooth transition for hover effect */
    }

    .schedule-button:hover {
        background-color: #C11B1B;
        /* Darker shade on hover */
    }
</style>
