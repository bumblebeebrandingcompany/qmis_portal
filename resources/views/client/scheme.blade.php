@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-content mt-3 px-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <img src="{{ asset('assets/vectors/group_3_x2.svg') }}" alt="Group Image" class="img-fluid"
                            style="width: 70px; height: 70px; margin-bottom: 10px;">
                    </div>
                    <div class="col-6">
                        <div class="row justify-content-end align-items-center">
                            <div class="col-auto">
                                <!-- Search Icon -->
                                <img src="{{ asset('assets/vectors/search_5_x2.svg') }}" alt="Search Icon"
                                    class="icon img-fluid" style="width: 24px; height: 24px; margin-right: 15px;">
                            </div>
                            <div class="col-auto">
                                <!-- Notifications Icon -->
                                <img src="{{ asset('assets/vectors/notifications_5_x2.svg') }}" alt="Notifications Icon"
                                    class="icon img-fluid" style="width: 24px; height: 24px; margin-right: 15px;">
                            </div>
                            <div class="col-auto">
                                <!-- Avatar -->
                                <div class="avatar d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; border-radius: 50%; background-color: rgba(210, 27, 33, 0.23); color: #F1416C; font-size: 16px;">
                                    <span class="aa">S</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-center mt-3" style="margin: 5%">
        <div class="col d-flex align-items-center">
            <!-- Custom Arrow Icon -->
            <img src="{{ asset('assets/vectors/arrow_4_x2.svg') }}" alt="Arrow Icon"
                style="width: 24px; height: 24px; margin-right: 8px; color: #F1416C;">
            <span style="color: #F1416C; font-weight: bold;">Choose the package</span>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col text-center">
            <span style="color: #10194A; font-weight: bold; font-size: 18px;">Pre-KG Fees and Benefits</span>
        </div>
    </div>

    <div class="container">
        @php
            $previousPlanName = null;
        @endphp

        @foreach ($packages as $package)
            @if ($package->plan->name !== $previousPlanName)
                <div class="row align-items-center mt-3" style="margin: 5%">
                    <div class="col d-flex align-items-center">
                        <span class="package-name" data-package-id="{{ $package->id }}"
                            style="color: #F1416C; font-weight: bold;">
                            {{ $package->plan->name ?? '' }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="container mt-4">
                <div class="card" style="position: relative; border-radius: 10px;" data-package-id="{{ $package->id }}">
                    <div class="elmgeneralindicatoractivesection"
                        style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #FF0000; margin-top: 5%; margin-bottom: 5%; border-radius: 10px">
                    </div>

                    <div class="card-content px-3 py-3">
                        <div class="row mt-2">
                            <div class="col-12">
                                <h5 style="font-size: 14px; font-weight: bold; color: #0A1629">{{ $package->type ?? '' }}
                                </h5>
                            </div>
                        </div>

                        <div class="row mt-2 d-flex align-items-center">
                            <div class="col-6">
                                <span style="color: #0A1629; font-size: 16px;">Normal Fees</span>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <span style="position: relative; font-size: 16px;">
                                    <span style="color: #0A1629;">
                                        {{ $package->amount ?? '' }}
                                    </span>
                                    <span
                                        style="position: absolute; left: 0; top: 50%; width: 100%; border-top: 1px solid #F1416C; transform: translateY(-50%);"></span>
                                </span>
                            </div>
                        </div>

                        <div class="row mt-2 d-flex align-items-center">
                            <div class="col-6">
                                <span style="color: #0A1629; font-size: 16px;">Vijayadhasmi Offer</span>
                            </div>
                            <div class="col-6 d-flex justify-content-end">
                                <span style="color: #0A1629; font-size: 16px;">
                                    {{ $package->offer ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $previousPlanName = $package->plan->name;
            @endphp
        @endforeach
        <div class="row align-items-center mt-3" style="margin: 5%">
            <div class="col d-flex align-items-center">
                <span style="color: #F1416C; font-weight: bold;">Add Ons:</span>
            </div>
        </div>
        <div id="selected-card-details" style="margin-top: 20px;margin-bottom: 25%;">
            <div class="row mt-2 d-flex align-items-center">
                <div class="col-6">
                    <span style="color: #0A1629; font-size: 16px;">
                        Transport<br>
                        (<span id="transport-price">0</span> per year)
                    </span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <span style="position: relative; font-size: 16px;">
                        <span style="color: #0A1629;" id="transport-price">₹0</span>
                        <span
                            style="position: absolute; left: 0; top: 50%; width: 100%;  transform: translateY(-50%);"></span>
                    </span>
                </div>
            </div>
            <div class="card"
                style="background-color: #D21B21; height: 50px; border: none; position: relative;margin-top:5%">
                <div class="card-body"
                    style="padding: 0; display: flex; align-items: center; justify-content: space-between; position: relative;">
                    <span style="color: #ffffff; font-size: 16px; margin-left: 10px;">Total</span>
                    <span
                        style="color: #ffffff; font-size: 16px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"
                        id="total">
                        ₹0
                    </span>
                </div>
            </div>

            <div class="row align-items-center mt-3" style="margin: 5%">
                <div class="col d-flex align-items-center">
                    <span style="color: #F1416C; font-weight: bold;">Offers Applied:</span>
                </div>
            </div>
            <div class="row mt-2 d-flex align-items-center">
                <div class="col-6">
                    <span style="color: #0A1629; font-size: 16px;">Admission fee waiver</span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <span style="position: relative; font-size: 16px;">
                        <span style="color: #0A1629;" id="admission-fee-waiver">₹0</span>
                        <span
                            style="position: absolute; left: 0; top: 50%; width: 100%; transform: translateY(-50%);"></span>
                    </span>
                </div>
            </div>
            <div class="row mt-2 d-flex align-items-center">
                <div class="col-6">
                    <span style="color: #0A1629; font-size: 16px;">Term 2 & 3 Fee Waiver (1st Year)</span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <span style="position: relative; font-size: 16px;">
                        <span style="color: #0A1629;" id="second-term-fee-waiver">₹0</span>
                        <span
                            style="position: absolute; left: 0; top: 50%; width: 100%;  transform: translateY(-50%);"></span>
                    </span>
                </div>
            </div>

            <div class="row mt-2 d-flex align-items-center">
                <div class="col-6">
                    <span style="color: #0A1629; font-size: 16px;">Kids Gym Waiver (1st year)</span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <span style="position: relative; font-size: 16px;">
                        <span style="color: #0A1629;" id="kids-gym-waiver">₹0</span>
                        <span
                            style="position: absolute; left: 0; top: 50%; width: 100%; transform: translateY(-50%);"></span>
                    </span>
                </div>
            </div>
            <div class="card"
                style="background-color: #0A1629; height: 50px; border: none; position: relative;margin-top:5%">
                <div class="card-body"
                    style="padding: 0; display: flex; align-items: center; justify-content: space-between; position: relative;">
                    <span style="color: #ffffff; font-size: 16px; margin-left: 10px;">Benefits Availed</span>
                    <span
                        style="color: #ffffff; font-size: 16px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"
                        id="benefits">
                        ₹0
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-container">
        <a class="schedule-button" style="font-weight: bold;text-align:center"
            href="{{ route('client.siteVisitForm', ['id' => $lead]) }}">SCHEDULE SITE
            VISIT</a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            const transportPrice = document.getElementById('transport-price');
            const admissionFeeWaiver = document.getElementById('admission-fee-waiver');
            const secondTermFeeWaiver = document.getElementById('second-term-fee-waiver');
            const kidsGymWaiver = document.getElementById('kids-gym-waiver');
            const total = document.getElementById('total');
            const benefits = document.getElementById('benefits');


            cards.forEach(card => {
                card.addEventListener('click', function() {
                    cards.forEach(c => c.classList.remove('selected'));

                    this.classList.add('selected');

                    const packageId = this.getAttribute('data-package-id');

                    updateDetails(packageId);
                });
            });

            function updateDetails(packageId) {
                const details = @json($packages->keyBy('id'));

                if (details[packageId]) {
                    transportPrice.textContent = `₹${details[packageId].transport_price}`;
                    admissionFeeWaiver.textContent = `₹${details[packageId].admission_fees}`;
                    secondTermFeeWaiver.textContent = `₹${details[packageId].waiver_term2}`;
                    kidsGymWaiver.textContent = `₹${details[packageId].kid_gym_waiver}`;
                    total.textContent = `₹${details[packageId].total_amount}`;
                    benefits.textContent = `₹${details[packageId].benefits_availed}`;
                }
            }
        });
    </script>
    <div class="modal fade" id="scheduleVisitModal" tabindex="-1" role="dialog"
        aria-labelledby="scheduleVisitModalLabel" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title custom-header">Book School Visit</h5>
                    <button type="button" class="close text-icon" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Form starts here -->
                <form action="{{ route('client.storeSiteVisit', ['id' => $lead]) }}" method="POST">
                    @csrf <!-- Laravel CSRF token for security -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="visitDate" class="custom-text">Visit Date:</label>
                            <input type="date" name="date" class="form-control custom-input" id="visitDate"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="timeOfDay" class="custom-text">Select Time of Day:</label>
                            <select class="form-control custom-input" name="time_of_day" id="timeOfDay" required>
                                <option value="">Select...</option>
                                <option value="morning">Morning</option>
                                <option value="afternoon">Afternoon</option>
                                <option value="evening">Evening</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timeSlot" class="custom-text">Available Time Slots:</label>
                            <div id="timeSlotContainer" class="d-flex flex-wrap"></div>
                            <!-- Add hidden input to capture the selected time slot -->
                            <input type="hidden" name="time_slot" id="selectedTimeSlot">
                        </div>
                        <div class="form-group">
                            <label for="description" class="custom-text">Description:</label>
                            <textarea id="description" name="description" class="form-control custom-input" rows="4"
                                placeholder="Enter additional details here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="display: flex; justify-content: center; padding: 5%;">
                        <button type="submit" class="schedule-button" style="width: 100%;">Book Now</button>
                    </div>
                </form>
                <!-- Form ends here -->
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('timeOfDay').addEventListener('change', function() {
            const timeSlotContainer = document.getElementById('timeSlotContainer');
            timeSlotContainer.innerHTML = ''; // Clear previous slots
            const selectedTime = this.value;
            let timeSlots = [];

            if (selectedTime === 'morning') {
                timeSlots = ['10:00 AM', '11:00 AM', '12:00 PM'];
            } else if (selectedTime === 'afternoon') {
                timeSlots = ['12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM'];
            } else if (selectedTime === 'evening') {
                timeSlots = ['03:00 PM', '04:00 PM', '05:00 PM', '06:00 PM'];
            }

            timeSlots.forEach(slot => {
                const slotButton = document.createElement('button');
                slotButton.type = 'button';
                slotButton.className = 'btn btn-outline-primary m-1';
                slotButton.innerText = slot;
                slotButton.addEventListener('click', function() {
                    // Remove 'selected' class from previously selected button
                    const previouslySelected = document.querySelector(
                        '#timeSlotContainer .btn.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                    }
                    // Add 'selected' class to the clicked button
                    this.classList.add('selected');
                });
                timeSlotContainer.appendChild(slotButton);
            });
        });
    </script>
    <style>
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

        .custom-modal-dialog {
            margin: 15%;
            /* Adds margin around the modal */
        }

        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap');

        /* Custom styles */
        .custom-input {
            border-color: #D8E0F0 !important;
            color: #7D8592;
            font-family: 'Nunito Sans', sans-serif;
        }

        .custom-text {
            color: #7D8592;
            font-family: 'Nunito Sans', sans-serif;
        }

        .custom-header {
            color: #0A1629;
            font-family: 'Nunito Sans', sans-serif;
        }

        /* Custom styles for the close icon */
        .text-icon span[aria-hidden="true"] {
            color: #0A1629;
            font-family: 'Nunito Sans', sans-serif;
        }

        .btn-primary {
            background-color: #7D8592;
            border-color: #7D8592;
            font-family: 'Nunito Sans', sans-serif;
        }

        .btn-primary:hover {
            background-color: #5c6470;
            border-color: #5c6470;
        }

        @media (max-width: 576px) {
            .custom-modal-dialog {
                margin: 5%;
                /* Less margin on smaller screens */
                max-width: 90%;
                /* Make modal wider on smaller screens */
            }
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            /* Center the button horizontally */
            padding: 0;
            /* Remove default padding */
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
            width: 100%;
            /* Make the button stretch */
            max-width: 300px;
            /* Optional: Set a max-width for better appearance */
            transition: background-color 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .schedule-button:hover {
            background-color: #C11B1B;
            /* Darker shade on hover */
        }
    </style>
@endsection
