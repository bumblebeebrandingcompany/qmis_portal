@extends('layouts.client')
@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-content mt-3 px-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <img src="{{ asset('assets/vectors/group_3_x2.svg') }}" alt="Group Image" class="img-fluid"
                            style="width: 70px; height: 70px;">
                    </div>
                    <div class="col-6">
                        <div class="row justify-content-end align-items-center">
                            <div class="col-auto">
                                <img src="{{ asset('assets/vectors/search_5_x2.svg') }}" alt="Search Icon"
                                    class="icon img-fluid" style="width: 24px; height: 24px; margin-right: 15px;">
                            </div>
                            <div class="col-auto">
                                <img src="{{ asset('assets/vectors/notifications_5_x2.svg') }}" alt="Notifications Icon"
                                    class="icon img-fluid" style="width: 24px; height: 24px; margin-right: 15px;">
                            </div>
                            <div class="col-auto">
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

    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #FF0000;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/vector_eligibility.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Elegibilty criteria</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Click to check if your kid is eligible to apply for our KG programms
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route('client.eligiblity_criteria',['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #51CD8A;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/vector_admission.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Admission Process</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Click here to learn about the about process to get into Queen Mira
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route('client.admission_process',['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #F6C000;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/vector_kg.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>KG Progams</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Click here to learn about your kids day at Queenmira Internaitonal School
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route('client.kg_program',['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #7239EA;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/vector_global.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Global standards</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Click here to learn about your standards and practices of the the school
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route('client.global_standards',['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #FF0000;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/vector_2_x2.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Accreditions</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo justo eget consectetur
                        efficitur. Nullam massa est, scelerisque at enim at, sodales volutpat dolor. In ut odio quis odio
                        maximus luctus. Curabitur blandit turpis at nibh fermentum, in aliquam risus faucibus. Nam commodo
                        massa ut leo sollicitudin, et fermentum magna blandit. Cras condimentum nibh nec urna consectetur,
                        nec cursus ex interdum...
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route("client.accreditions",['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #51CD8A;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/pre-kg.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Initiatives</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo justo eget consectetur
                        efficitur. Nullam massa est, scelerisque at enim at, sodales volutpat dolor. In ut odio quis odio
                        maximus luctus. Curabitur blandit turpis at nibh fermentum, in aliquam risus faucibus. Nam commodo
                        massa ut leo sollicitudin, et fermentum magna blandit. Cras condimentum nibh nec urna consectetur,
                        nec cursus ex interdum...
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route("client.initiatives",['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #F6C000;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/kg-1.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>QMIS Learning</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo justo eget consectetur
                        efficitur. Nullam massa est, scelerisque at enim at, sodales volutpat dolor. In ut odio quis odio
                        maximus luctus. Curabitur blandit turpis at nibh fermentum, in aliquam risus faucibus. Nam commodo
                        massa ut leo sollicitudin, et fermentum magna blandit. Cras condimentum nibh nec urna consectetur,
                        nec cursus ex interdum...
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route("client.qmisLearning",['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4" style="margin-bottom:30%">
        <div class="card" style="position: relative;">
            <div class="elmgeneralindicatoractivesection"
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #7239EA;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/kg-2.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Early Child Programs</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo justo eget consectetur
                        efficitur. Nullam massa est, scelerisque at enim at, sodales volutpat dolor. In ut odio quis odio
                        maximus luctus. Curabitur blandit turpis at nibh fermentum, in aliquam risus faucibus. Nam commodo
                        massa ut leo sollicitudin, et fermentum magna blandit. Cras condimentum nibh nec urna consectetur,
                        nec cursus ex interdum...
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{route("client.earlyChild",['id'=>$lead])}}" class="btn btn-link p-0 d-flex align-items-center">
                            View details <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-container">
        <a class="schedule-button" style="font-weight: bold;text-align:center" href="{{route('client.oneStep',['id'=>$lead])}}">SECURE THEIR SPOT</a>
    </div>

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
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="visitDate" class="custom-text">Visit Date:</label>
                            <input type="date" class="form-control custom-input" id="visitDate" required>
                        </div>
                        <div class="form-group">
                            <label for="timeOfDay" class="custom-text">Select Time of Day:</label>
                            <select class="form-control custom-input" id="timeOfDay" required>
                                <option value="">Select...</option>
                                <option value="morning">Morning</option>
                                <option value="afternoon">Afternoon</option>
                                <option value="evening">Evening</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timeSlot" class="custom-text">Available Time Slots:</label>
                            <div id="timeSlotContainer" class="d-flex flex-wrap"></div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="custom-text">Description:</label>
                            <textarea id="description" class="form-control custom-input" rows="4"
                                placeholder="Enter additional details here..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center; padding: 5%;">
                    <button type="button" class="schedule-button" style="width: 100%;">Book Now</button>
                </div>
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
    <style>
        .btn-outline-primary {
            border-color: #7D8592;
            color: #7D8592;
        }

        .btn-outline-primary.active,
        .btn-outline-primary:focus,
        .btn-outline-primary.selected {
            background-color: #10194A;
            border-color: #10194A;
            color: white;
        }
    </style>
@endsection
