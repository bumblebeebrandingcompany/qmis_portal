<!DOCTYPE html>
<html>
<head>
    <title>QR Code PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            border: 3px solid #000B6B;
        }
        table {
            width:100%;
        }
        footer{
            position:fixed;
            bottom:5px;
        }
        .p-2 {
            padding: 0.5rem;
        }
        .header_font, th {
            font-family: Nunito Sans;
            font-size: 15px;
            font-weight: 500;
            line-height: 10px;
        }
        .header_font_content {
            font-family: Nunito Sans;
            font-size: 12px;
            font-weight: 20;
            line-height: 10px;
        }
        .app_content {
            font-family: Nunito Sans;
            font-size: 20px;
            font-weight: 500;
            line-height: 16px;
            color:#D21B21;
        }
        hr {
            color:#D21B21;
        }
        .text-center {
            text-align:center;
        }
        .border_bottom {
            /* border-bottom: 1px solid black; */
            font-weight: 10;
            line-height: 20px;
        }
        .p-4 {
            margin-top: 2rem;
        }
        td{
            font-family: Nunito Sans;
            font-size: 16px;
            font-weight: 20;
            line-height: 15px;
        }
        th {
            text-align:left;
        }
        .pt-2 {
            margin-top:0.5rem;
        }
        .mb-2 {
            margin-bottom:0.5rem;
        }
        .pdf_image{
            background: cover;
            width: 100%;
        }
    </style>
</head>
<body>
    <section>
        <div class="p-2">

            <table>
                <tr>
                    <td>
                        <img src="{{public_path('assets/images/qmis.svg')}}" width="50">
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td class="header_font">Queen Mira International School</td>
                            </tr>
                            <tr>
                                <td class="header_font_content">Sholavandhan Road, Melakkal Rd, Kochadai, Madurai, Tamil Nadu 625019</td>
                            </tr>
                            <tr>
                                <td class="header_font_content">Phone: 097875 70746</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <div class="app_content">Application Form</div>
                    </td>
                </tr>
            </table>
            <hr>
            <div class="text-center p-4 header_font"><b>Application Details</b></div>
            <table>
                <tr>
                    <td width=90%>
                        <table>
                            <tr><td class="border_bottom">Application Number:  {{$application->application_no}}</td></tr>
                            <tr><td class="border_bottom">Date of Application: {{date('d/m/Y', strtotime($application->created_at))}}</td></tr>
                            <tr><td class="border_bottom">Application Status:  Got Application</td></tr>
                            <tr class=""><td>Scheme:  -</td></tr>
                        </table>
                    </td>
                    <td width=10%><img width="50" height="50" src="{{ $qrCode }}" alt="QR Code" style="float:right;margin-top:1rem;"></td>
                </tr>
            </table>
            <div class="text-center p-4 header_font" style="margin-bottom: 20px;"><b>Child Details</b></div>

            @php
            $student = @$application->lead->student_details;
            @endphp
            @if(@$application->lead->student_details)
            <table class="pt-2">
                <thead>
                    <tr>
                        <th></th>
                        @foreach(@$student as $k =>  $s_head)
                        <th>Child {{$k+1}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border_bottom"><b>Child Name</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td class="border_bottom">{{isset($s_head['name']) ? $s_head['name'] :'-'}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Date of Birth</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td class="border_bottom">{{isset($s_head['dob']) ? $s_head['dob'] :'-'}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Age</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td class="border_bottom">{{isset($s_head['age']) ? $s_head['age'] :'-'}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Blood Group</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td class="border_bottom">{{isset($s_head['blood_group']) ? $s_head['blood_group'] :'-'}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Applying For</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td class="border_bottom">{{isset($s_head['reason_for_quit']) ? $s_head['reason_for_quit'] :'-'}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Current Grade</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td class="border_bottom">{{isset($s_head['grade']) ? $s_head['grade'] :'-'}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><b>Previous School</b></td>
                        @foreach(@$student as $k =>  $s_head)
                        <td>{{isset($s_head['old_school']) ? $s_head['old_school'] :'-'}}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
            @else 
            <div class="text-center header_font_content">Child Not added.</div>
            @endif
            
            <div class="text-center p-4 header_font" style="margin-bottom: 20px;"><b>Parent Details</b></div>
            
            @php
            $father = @$application->lead->father_details;
            $mother = @$application->lead->mother_details;
            $guardian = @$application->lead->guardian_details;
            @endphp
            <table class="pt-2">
                <thead>
                    <th></th>
                    <th>Father</th>
                    <th>Mother</th>
                    <th>Guardian</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="border_bottom"><b>Name</b></td>
                        <td class="border_bottom">{{isset($father['name']) ? $father['name'] :'-'}}</td>
                        <td class="border_bottom">{{isset($mother['name']) ? $mother['name'] :'-'}}</td>
                        <td class="border_bottom">{{isset($guardian['name']) ? $guardian['name'] :'-'}}</td>
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Relationship</b></td>
                        <td class="border_bottom">Father</td>
                        <td class="border_bottom">Mother</td>
                        <td class="border_bottom">{{isset($guardian['relationship']) ? $guardian['relationship'] :'-'}}</td>
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Age</b></td>
                        <td class="border_bottom">{{isset($father['age']) ? $father['age'] :'-'}}</td>
                        <td class="border_bottom">{{isset($mother['age']) ? $mother['age'] :'-'}}</td>
                        <td class="border_bottom">{{isset($guardian['age']) ? $guardian['age'] :'-'}}</td>
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Mobile Number</b></td>
                        <td class="border_bottom">{{isset($father['phone']) ? $father['phone'] :'-'}}</td>
                        <td class="border_bottom">{{isset($mother['phone']) ? $mother['phone'] :'-'}}</td>
                        <td class="border_bottom">{{isset($guardian['phone']) ? $guardian['phone'] :'-'}}</td>
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Email ID</b></td>
                        <td class="border_bottom">{{isset($father['email']) ? $father['email'] :'-'}}</td>
                        <td class="border_bottom">{{isset($mother['email']) ? $mother['email'] :'-'}}</td>
                        <td class="border_bottom">{{isset($guardian['email']) ? $guardian['email'] :'-'}}</td>
                    </tr>
                    <tr>
                        <td class="border_bottom"><b>Occupation</b></td>
                        <td class="border_bottom">{{isset($father['occupation']) ? $father['occupation'] :'-'}}</td>
                        <td class="border_bottom">{{isset($mother['occupation']) ? $mother['occupation'] :'-'}}</td>
                        <td class="border_bottom">{{isset($guardian['occupation']) ? $guardian['occupation'] :'-'}}</td>
                    </tr>
                    <tr>
                        <td><b>Anuual Income</b></td>
                        <td>{{isset($father['income']) ? $father['income'] :'-'}}</td>
                        <td>{{isset($mother['income']) ? $mother['income'] :'-'}}</td>
                        <td>{{isset($guardian['income']) ? $guardian['income'] :'-'}}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center p-4 header_font" style="margin-bottom: 20px;"><b>Residential Address</b></div>

            @php
            $common = @$application->lead->common_details;
            @endphp
            <table class="pt-2">
                <tr>
                    <td class="">{{$common}}</td>
                </tr>
            </table>

            <footer>
                <table>
                    <tr>
                        <td>{{@$application->lead->ip_address}}</td>
                    </tr>
                </table>
            </footer>
        </div>
    </section>
    <section>
        <div class="p-2">
            @php
            $student = @$application->lead ? @$application->lead->student_details : [];
            @endphp
            <div>
                @if (count($student) > 0)
                    @php
                        $grade = @$student[0]['grade'];
                        if ($grade == 'kg 1') {
                            $src = "{{ public_path('assets/images/pdf_kg1.png') }}";
                        } else if ($grade == 'kg 2'){
                            $src = "{{ public_path('assets/images/pdf_kg2.png') }}";
                        } else if ($grade == 'play home'){
                            $src = "{{ public_path('assets/images/pdf_home.png') }}";
                        } else {
                            $src = "{{ public_path('assets/images/pdf_image.png') }}";
                        }
                    @endphp
                    <img src="{{$src}}" alt="PDF Backend" class="pdf_image">
                @else
                    <img src="{{ public_path('assets/images/pdf_image.png') }}" alt="PDF Backend" class="pdf_image">
                @endif
            </div>
        </div>
    </section>
</body>
</html>
