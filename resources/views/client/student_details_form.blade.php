@extends('layouts.client')
@section('content')
    <div class="d-flex justify-content-center align-items-center mb-4" style="margin: 10%; width=160px; height:77px;">
        <img src="{{ asset('assets/vectors/qmis_logo_3_x2.svg') }}" alt="Logo" style="width: 150px; height: auto;">
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Child Details</div>
                    <div class="card-body">
                        <form action="{{ route('client.updateStudentDetails', ['id' => $lead->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                            <div id="child-forms">
                                @php
                                    $studentDetails = $studentDetails ?? [];
                                    $initialForm = empty($studentDetails)
                                        ? '<div class="child-form mb-3" data-index="0"><h5>Child 1</h5><div class="form-group"><label for="child_name_0">Name of the Child:<span class="text-danger">*</span></label><input type="text" id="child_name_0" name="student_details[0][name]" class="form-control"></div><div class="form-group"><label for="child_dob_0">Date of Birth:<span class="text-danger">*</span></label><input type="date" id="child_dob_0" name="student_details[0][dob]" class="form-control"></div><div class="form-group"><label for="child_grade_0">Grade:<span class="text-danger">*</span></label><select id="child_grade_0" name="student_details[0][grade]" class="form-control grade-select"><option value="">Select Grade</option><option value="play home">Play Home</option><option value="pre kg">Pre KG</option><option value="kg 1">KG 1</option>
                                                    <option value="kg 2">KG 2</option>
                                                    <option value="1">Grade 1</option>
                                                    <option value="2">Grade 2</option>
                                                    <option value="3">Grade 3</option>
                                                    <option value="4">Grade 4</option>
                                                    <option value="5">Grade 5</option>
                                                    <option value="6">Grade 6</option>
                                                    <option value="7">Grade 7</option>
                                                    <option value="8">Grade 8</option>
                                                    <option value="9">Grade 9</option>
                                                    <option value="10">Grade 10</option>
                                                    <option value="11">Grade 11</option>
                                                    <option value="12">Grade 12</option>
                                                </select>



                                            <div class="form-group">
                                                                         
                                                <label for="gender_0">Gender:<span class="text-danger">*</span></label>
                                                <select id="gender_0"
                                                    name="student_details[0][gender]"
                                                    class="form-control gender-select">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    </select>
                                                  
                                            </div>
                                            </div>
                                            <label for="child_blood_0">Blood Group:<span class="text-danger">*</span></label><input type="text" id="child_blood_0" name="student_details[0][blood]" class="form-control">
                                            <div class="form-group old-school-group"><label for="child_old_school_0">Name of the Previous School:</label><input type="text" id="child_old_school_0" name="student_details[0][old_school]" class="form-control"></div><div class="form-group reason-group"><label for="child_reason_0">Reason for Transfer:</label><textarea id="child_reason_0" name="student_details[0][reason_for_quit]" class="form-control"></textarea></div></div>'
                                        : '';
                                @endphp
                                {!! $initialForm !!}
                                @if (is_array($studentDetails) || is_object($studentDetails))
                                    @foreach ($studentDetails as $index => $student)
                                        <div class="child-form mb-3" data-index="{{ $index }}">
                                            <h5>Child {{ $index + 1 }}</h5>
                                            <div class="form-group">
                                                <label for="child_name_{{ $index }}">Name of the Child:</label>
                                                <input type="text" id="child_name_{{ $index }}"
                                                    name="student_details[{{ $index }}][name]"
                                                    value="{{ $student['name'] }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="child_dob_{{ $index }}">Date of Birth:</label>
                                                <input type="date" id="child_dob_{{ $index }}"
                                                    name="student_details[{{ $index }}][dob]"
                                                    value="{{ $student['dob'] }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="child_grade_{{ $index }}">Grade to be Enrolled for: </label>
                                                <select id="child_grade_{{ $index }}"
                                                    name="student_details[{{ $index }}][grade]"
                                                    class="form-control grade-select">  
                                                    <option value="">Select Grade</option>
                                                    <option value="play home"
                                                        {{ $student['grade'] == 'play home' ? 'selected' : '' }}>Play Home
                                                    </option>
                                                    <option value="pre kg"
                                                        {{ $student['grade'] == 'pre kg' ? 'selected' : '' }}>Pre KG
                                                    </option>
                                                    <option value="kg 1"
                                                        {{ $student['grade'] == 'kg 1' ? 'selected' : '' }}>KG 1</option>
                                                    <option value="kg 2"
                                                        {{ $student['grade'] == 'kg 2' ? 'selected' : '' }}>KG 2</option>
                                                    <option value="1"
                                                        {{ $student['grade'] == '1' ? 'selected' : '' }}>Grade 1
                                                    </option>
                                                    <option value="3"
                                                        {{ $student['grade'] == '3' ? 'selected' : '' }}>Grade 3
                                                    </option>
                                                    <option value="4"
                                                        {{ $student['grade'] == '4' ? 'selected' : '' }}>Grade 4
                                                    </option>
                                                    <option value="5"
                                                        {{ $student['grade'] == '5' ? 'selected' : '' }}>Grade 5
                                                    </option>
                                                    <option value="6"
                                                        {{ $student['grade'] == '6' ? 'selected' : '' }}>Grade 6
                                                    </option>
                                                    <option value="7"
                                                        {{ $student['grade'] == '7' ? 'selected' : '' }}>Grade 7
                                                    </option>
                                                    <option value="8"
                                                        {{ $student['grade'] == '8' ? 'selected' : '' }}>Grade 8
                                                    </option>
                                                    <option value="9"
                                                        {{ $student['grade'] == '9' ? 'selected' : '' }}>Grade 9
                                                    </option>
                                                    <option value="2"
                                                        {{ $student['grade'] == '2' ? 'selected' : '' }}>Grade 2
                                                    </option>
                                                    <option value="10"
                                                        {{ $student['grade'] == '10' ? 'selected' : '' }}>Grade 10
                                                    </option>
                                                    <option value="11"
                                                        {{ $student['grade'] == '11' ? 'selected' : '' }}>Grade 11
                                                    </option>
                                                    <option value="12"
                                                        {{ $student['grade'] == '12' ? 'selected' : '' }}>Grade 12
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="child_gender_{{ $index }}">Gender:</label>
                                                <select id="child_gender_{{ $index }}"
                                                    name="student_details[{{ $index }}][gender]"
                                                    class="form-control gender-select">
                                                    <option value="">Select Gender</option>
                                                    <option value="male"
                                                        {{ isset($student['gender']) && $student['gender'] == 'male' ? 'selected' : '' }}>
                                                        Male
                                                    </option>
                                                    <option value="female"
                                                        {{ isset($student['gender']) && $student['gender'] == 'female' ? 'selected' : '' }}>
                                                        Female
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group blood-group">
                                                <label for="child_blood_{{ $index }}">Blood Group:</label>
                                                <input type="text" id="child_blood_{{ $index }}"
                                                    name="student_details[{{ $index }}][blood]"
                                                    value="{{ $student['blood'] }}" class="form-control">
                                            </div>
                                            <div class="form-group old-school-group">
                                                <label for="child_old_school_{{ $index }}">Name of the Previous School:</label>
                                                <input type="text" id="child_old_school_{{ $index }}"
                                                    name="student_details[{{ $index }}][old_school]"
                                                    value="{{ $student['old_school'] }}" class="form-control">
                                            </div>
                                            <div class="form-group reason-group">
                                                <label for="child_reason_{{ $index }}">Reason for
                                                Transfer:</label>
                                                <textarea id="child_reason_{{ $index }}" name="student_details[{{ $index }}][reason_for_quit]"
                                                    class="form-control">{{ $student['reason_for_quit'] }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            {{-- <button type="button" id="add-child-btn" class="btn btn-secondary">Add Another
                                Child</button> --}}
                            <button type="submit" class="btn btn-primary w-100"
                                style="background-color:#10194A">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="add-child-btn"style="background-color:#10194A" class="fab">
        <img src="{{ asset('/assets/vectors/add_1_x2.svg') }}" alt="Add Child Icon">
    </button>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let childCount = $('#child-forms .child-form').length;

            function handleGradeChange(form) {
                const grade = form.find('.grade-select').val();
                if (grade === 'pre kg' || grade === 'play home') {
                    form.find('.old-school-group').hide();
                    form.find('.reason-group').hide();
                } else {
                    form.find('.old-school-group').show();
                    form.find('.reason-group').show();
                }
            }

            $('.grade-select').each(function() {
                handleGradeChange($(this).closest('.child-form'));
            });

            $('#add-child-btn').click(function() {
                childCount++;
                const newFormIndex = childCount - 1;
                const newForm = `
                    <div class="child-form mb-3" data-index="${newFormIndex}">
                        <h5>Child ${childCount}</h5>
                        <div class="form-group">
                            <label for="child_name_${newFormIndex}">Name of the Child:</label>
                            <input type="text" id="child_name_${newFormIndex}" name="student_details[${newFormIndex}][name]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="child_dob_${newFormIndex}">Date of Birth:</label>
                            <input type="date" id="child_dob_${newFormIndex}" name="student_details[${newFormIndex}][dob]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="child_grade_${newFormIndex}">Grade to be Enrolled for:</label>
                            <select id="child_grade_${newFormIndex}" name="student_details[${newFormIndex}][grade]" class="form-control grade-select">
                                <option value="">Select Grade</option>
                                                                <option value="play home">Play Home</option>

                                <option value="pre kg">Pre KG</option>
                                <option value="kg 1">KG 1</option>
                                <option value="kg 2">KG 2</option>
                                <option value="1">Grade 1</option>
                                <option value="2">Grade 2</option>
                                <option value="3">Grade 3</option>
                                <option value="4">Grade 4</option>
                                <option value="5">Grade 5</option>
                                <option value="6">Grade 6</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        <div class="form-group">
                                                <label for="gender_${ newFormIndex }">Gender:</label>
                                                <select id="gender_${newFormIndex }"
                                                    name="student_details[${ newFormIndex }][gender]"
                                                    class="form-control gender-select">
                                                    <option value="">Select Gender</option>
                                                    <option value="male"
                                                        >Male
                                                    </option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                             <div class="form-group blood-group">
                            <label for="child_blood_${newFormIndex}">Blood Group:</label>
                            <input type="text" id="child_blood_${newFormIndex}" name="student_details[${newFormIndex}][blood]" class="form-control">
                        </div>
                        <div class="form-group old-school-group">
                            <label for="child_old_school_${newFormIndex}">Old School:</label>
                            <input type="text" id="child_old_school_${newFormIndex}" name="student_details[${newFormIndex}][old_school]" class="form-control">
                        </div>
                        <div class="form-group reason-group">
                            <label for="child_reason_${newFormIndex}">Reason for Transfer</label>
                            <textarea id="child_reason_${newFormIndex}" name="student_details[${newFormIndex}][reason_for_quit]" class="form-control"></textarea>
                        </div>
                    </div>
                `;
                $('#child-forms').append(newForm);
            });

            $(document).on('change', '.grade-select', function() {
                handleGradeChange($(this).closest('.child-form'));
            });
        });
    </script>
    <style>
        .fab {
            position: fixed;
            bottom: 20px;
            /* Distance from bottom */
            right: 20px;
            /* Distance from right */
            width: 60px;
            /* Button width */
            height: 60px;
            /* Button height */
            border-radius: 50%;
            background-color: #007bff;
            /* Button background color */
            border: none;
            /* No border */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Shadow effect */
            cursor: pointer;
            /* Pointer cursor */
            z-index: 1000;
        }

        .fab img {
            width: 24px;
            /* Icon width */
            height: 24px;
            /* Icon height */
        }
    </style>
@endsection
