@extends('layouts.client')
@section('content')
    <div class="container mt-4">
        <p>Your Interview Details</p>
        <p>Thank you once again for scheduling your interview! Here are the details of your upcoming session:</p>
        <li><b>Interview Date:</b>{{$siteVisit->date}} </li>
        <li><b>Interview Time:</b> {{$siteVisit->time_slot}}</li>
        <li><b>Location:</b> Queen Mira International School,<a href="https://maps.google.com/maps/place//data=!4m2!3m1!1s0x3b00cf295cdadddd:0x9ea7866071b1aa99?entry=s&sa=X&ved=1t:8290&hl=en-in&ictx=111">Click here</a></li>
        <li><b>Site Visit Number:</b> {{$siteVisit->ref_num}}</li>
        <h3>What to Expect:
        </h3>
        <li>A 45-minute school tour guided by our Admission Counselor.</li>
        <li>A free demo class for your child.</li>
        <li>A psychometric analysis</li>
        <li>A personalized interview and child assessment.</li>
        <li>A comprehensive scorecard detailing your child's personality and skillset.</li>
        <li>A chance to ask any questions or clear doubts.</li>
        <li>If you need to reschedule or have any questions, please contact us at 7897899389.</li>
        <p>We look forward to meeting you and your child!</p>
        <div><span>You can download the Application form</span><a href="{{route('qrdownload', ['no' => @$lead->application->application_no])}}" target="_blank" class="mx-1">Here</a></div>
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
                        <h5> Do’s and Dont’s
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Essential tips to help guide your child's smooth transition into school life.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/HVCO2 Do\'s and Don\'ts.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <img src="{{ asset('assets/vectors/kg-2.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Growth Chart
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Track your child's developmental milestones and progress in every stage of learning.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/Your child\'s growth chart.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <h5>A week in KG
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Get a glimpse of the engaging and enriching activities your child will experience in a typical week at KG.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/Sneak Peek of KG Class.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <h5>QMIS vs Others
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Discover why Queen Mira stands out among the best schools in the region.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/HVCO3 QMIS vs Others.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <h5>School Comparison
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>A comprehensive comparison of top schools to help you choose the best for your child.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/School Comparison Form (VD).pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <h5>QMIS Alumnis
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>See the success stories of our alumni and how Queen Mira shaped their bright futures.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/QMIS_Alumni Handbook_HVCO_Dec 2023.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <img src="{{ asset('assets/vectors/kg-2.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Skill Comparison
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Understand the key skills your child will develop at Queen Mira compared to other schools.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/March QMIS vs Traditional Schools.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background-color: #F1416C;margin-top:5%;margin-bottom:5%;border-radius:10%">
            </div>

            <div class="card-content px-3 py-3">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('assets/vectors/person.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Brochure</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Get a complete overview of everything Queen Mira has to offer, from academics to
                            extracurriculars.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/QMIS_Brochure_compressed.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <img src="{{ asset('assets/vectors/checklist.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Child Persona
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Gain personalized insights into your child’s unique learning style and personality.
                        </span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/HVCO1 Understanding child\'s persona.pdf') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <img src="{{ asset('assets/vectors/videos.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>20 Tips for Parents from pre school teachers
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Expert tips from our seasoned educators to help you support your child’s early learning.
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/pdf/May HVCO_20 Tips for Parents From Preschool Teachers.pdf') }}"
                            target="_blank" class="btn btn-link p-0 d-flex align-items-center">
                            Download <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
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
                        <img src="{{ asset('assets/vectors/kg-2.svg') }}" alt="Icon" class="img-fluid"
                            style="width: 26px; height: 27px;">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h5>Fees structures
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">

                        <span>A transparent breakdown of the fees and the value of investing in your child’s education at
                            QMIS.</span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">


                        @php
                            $student = @$lead->student_details;
                        @endphp

                        @if (@$student[0]['grade'] == 'kg 1')
                            <a href="{{ asset('assets/images/pdf_kg1.png') }}"
                                class="btn btn-link p-0 d-flex align-items-center" target="_blank">View
                                <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                    class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;"></a>
                        @elseif(@$student[0]['grade'] == 'kg 2')
                            <a href="{{ asset('assets/images/pdf_kg2.png') }}"
                                class="btn btn-link p-0 d-flex align-items-center" target="_blank">View
                                <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                    class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;"></a>
                        @elseif(@$student[0]['grade'] == 'play home')
                            <a href="{{ asset('assets/images/pdf_home.png') }}"
                                class="btn btn-link p-0 d-flex align-items-center" target="_blank">View
                                <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                    class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;"></a>
                        @else
                            <a href="{{ asset('assets/images/pdf_image.png') }}"
                                class="btn btn-link p-0 d-flex align-items-center" target="_blank">View
                                <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                    class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;"></a>
                        @endif

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
                        <h5>Vijayadhasami Offers
                        </h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12"
                        style="max-height: 4.8em; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; line-height: 1.2em;">
                        <span>Take advantage of our limited-time offers to give your child the best start at unbeatable
                            rates!
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ asset('assets/images/Vijayadhasami.png') }}" target="_blank"
                            class="btn btn-link p-0 d-flex align-items-center">
                            View <img src="{{ asset('assets/vectors/arrow_38_x2.svg') }}" alt="Chevron Right"
                                class="img-fluid" style="width: 16px; height: 16px; margin-left: 5px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>

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
