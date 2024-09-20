@extends('layouts.client')
@section('content')
    <div class="d-flex justify-content-center align-items-center mb-4" style="margin: 10%; width=160px; height:77px;">
        <img src="{{ asset('assets/vectors/qmis_logo_3_x2.svg') }}" alt="Logo" style="width: 150px; height: auto;">
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        @php
                            $totalFields = 18;
                            $filledFields = 0;

                            $filledFields += !empty($lead->father_details['name']) ? 1 : 0;
                            $filledFields += !empty($lead->father_details['phone']) ? 1 : 0;
                            $filledFields += !empty($lead->father_details['email']) ? 1 : 0;
                            $filledFields += !empty($lead->father_details['occupation']) ? 1 : 0;
                            $filledFields += !empty($lead->father_details['income']) ? 1 : 0;
                            $filledFields += !empty($lead->mother_details['name']) ? 1 : 0;
                            $filledFields += !empty($lead->mother_details['phone']) ? 1 : 0;
                            $filledFields += !empty($lead->mother_details['email']) ? 1 : 0;
                            $filledFields += !empty($lead->mother_details['occupation']) ? 1 : 0;
                            $filledFields += !empty($lead->mother_details['income']) ? 1 : 0;
                            $filledFields += !empty($lead->guardian_details['name']) ? 1 : 0;
                            $filledFields += !empty($lead->guardian_details['relationship']) ? 1 : 0;
                            $filledFields += !empty($lead->guardian_details['phone']) ? 1 : 0;
                            $filledFields += !empty($lead->guardian_details['email']) ? 1 : 0;
                            $filledFields += !empty($lead->guardian_details['occupation']) ? 1 : 0;
                            $filledFields += !empty($lead->guardian_details['income']) ? 1 : 0;

                            $percentage = ($filledFields / $totalFields) * 100;

                            $avatarColor = '#FF0000'; // Default color: red for low completion
                            if ($percentage >= 75) {
                                $avatarColor = '#00FF00'; // Green for high completion
                            } elseif ($percentage >= 50) {
                                $avatarColor = '#FFFF00';
                            }
                        @endphp

                        <!-- Avatar with percentage -->
                        <div class="avatar mb-2 position-relative"
                            style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px;">
                            {{ round($percentage) }}%
                        </div>

                        <div>
                            <h5 class="mb-0">
                                @if ($lead->father_details['name'])
                                    {{ $lead->father_details['name'] }}
                                @elseif ($lead->mother_details['name'])
                                    {{ $lead->mother_details['name'] }}
                                @elseif ($lead->guardian_details['name'])
                                    {{ $lead->guardian_details['name'] }}
                                @else
                                    No Name Available
                                @endif
                            </h5>
                        </div>

                        <div class="relationship mt-2">
                            @if ($lead->father_details['name'])
                                Father
                            @elseif ($lead->mother_details['name'])
                                Mother
                            @elseif ($lead->guardian_details['name'])
                                {{ $lead->guardian_details['relationship'] }}
                            @else
                                No Relationship Available
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="updateDetailsForm" action="{{ route('client.updateParentDetails', ['id' => $lead->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <h5 style="color:#10194A;font-weight:bold">Main info</h5>
                            <!-- Father Details -->
                            <div class="form-group">
                                <label for="father_name" style="color:#7D8592">Father's Name<span class="text-danger">*</span></label>
                                <input type="text" id="father_name" name="father[name]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('father.name', $lead->father_details['name'] ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="father_phone" style="color:#7D8592">Father's Phone<span class="text-danger">*</span></label>
                                <input type="number" id="father_phone" name="father[phone]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('father.phone', $lead->father_details['phone'] ?? '') }}" required  oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="form-group">
                                <label for="father_email" style="color:#7D8592">Father's Email<span class="text-danger">*</span></label>
                                <input type="email" id="father_email" name="father[email]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('father.email', $lead->father_details['email'] ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="father_occupation" style="color:#7D8592">Father's Occupation<span class="text-danger">*</span></label>
                                <input type="text" id="father_occupation" name="father[occupation]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('father.occupation', $lead->father_details['occupation'] ?? '') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="father_income" style="color:#7D8592">Father's Income<span class="text-danger">*</span></label>
                                <input type="text" id="father_income" name="father[income]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('father.income', $lead->father_details['income'] ?? '') }}" required>
                            </div>

                            <!-- Mother Details -->
                            <div class="form-group">
                                <label for="mother_name" style="color:#7D8592">Mother's Name<span class="text-danger">*</span></label>
                                <input type="text" id="mother_name" name="mother[name]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('mother.name', $lead->mother_details['name'] ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="mother_phone" style="color:#7D8592">Mother's Phone<span class="text-danger">*</span></label>
                                <input type="number" id="mother_phone" name="mother[phone]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('mother.phone', $lead->mother_details['phone'] ?? '') }}" required  oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="form-group">
                                <label for="mother_email" style="color:#7D8592">Mother's Email<span class="text-danger">*</span></label>
                                <input type="email" id="mother_email" name="mother[email]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('mother.email', $lead->mother_details['email'] ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="mother_occupation" style="color:#7D8592">Mother's Occupation<span class="text-danger">*</span></label>
                                <input type="text" id="mother_occupation" name="mother[occupation]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('mother.occupation', $lead->mother_details['occupation'] ?? '') }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="mother_income" style="color:#7D8592">Mother's Income<span class="text-danger">*</span></label>
                                <input type="text" id="mother_income" name="mother[income]" class="form-control" 
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('mother.income', $lead->mother_details['income'] ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="guardian_name" style="color:#7D8592">Guardian's Name</label>
                                <input type="text" id="guardian_name" name="guardian[name]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('guardian.name', $lead->guardian_details['name'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="guardian_relationship" style="color:#7D8592">Relationship to the Student:</label>
                                <select id="guardian_relationship" name="guardian[relationship]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592">
                                    <option value="">Select Relationship</option>
                                    <option value="Aunt"
                                        {{ old('guardian.relationship', $lead->guardian_details['relationship'] ?? '') == 'Aunt' ? 'selected' : '' }}>
                                        Aunt</option>
                                    <option value="Uncle"
                                        {{ old('guardian.relationship', $lead->guardian_details['relationship'] ?? '') == 'Uncle' ? 'selected' : '' }}>
                                        Uncle</option>
                                    <option value="Friend"
                                        {{ old('guardian.relationship', $lead->guardian_details['relationship'] ?? '') == 'Friend' ? 'selected' : '' }}>
                                        Friend</option>
                                    <option value="GrandMa"
                                        {{ old('guardian.relationship', $lead->guardian_details['relationship'] ?? '') == 'GrandMa' ? 'selected' : '' }}>
                                        GrandMa</option>
                                    <option value="Grandpa"
                                        {{ old('guardian.relationship', $lead->guardian_details['relationship'] ?? '') == 'Grandpa' ? 'selected' : '' }}>
                                        Grandpa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="guardian_phone" style="color:#7D8592">Guardian's Phone</label>
                                <input type="number" id="guardian_phone" name="guardian[phone]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('guardian.phone', $lead->guardian_details['phone'] ?? '') }}"  oninput="this.value = this.value.slice(0, 10)">
                            </div>
                            <div class="form-group">
                                <label for="guardian_email" style="color:#7D8592">Guardian's Email</label>
                                <input type="email" id="guardian_email" name="guardian[email]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('guardian.email', $lead->guardian_details['email'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="guardian_occupation" style="color:#7D8592">Guardian's Occupation</label>
                                <input type="text" id="guardian_occupation" name="guardian[occupation]"
                                    class="form-control" style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('guardian.occupation', $lead->guardian_details['occupation'] ?? '') }}"
                                    >
                            </div>
                            <div class="form-group">
                                <label for="guardian_income" style="color:#7D8592">Guardian's Income</label>
                                <input type="text" id="guardian_income" name="guardian[income]" class="form-control"
                                    style="border-color:#D8E0F0;color:#7D8592"
                                    value="{{ old('guardian.income', $lead->guardian_details['income'] ?? '') }}"
                                    >
                            </div>
                            <button type="submit"
                                class="btn btn-primary d-flex align-items-center justify-content-center"
                                style="background-color: #10194A; width: 100%;">
                                <span class="me-2">Next</span>
                                <img src="{{ asset('assets/vectors/arrow_9_x2.svg') }}" alt="Next Icon"
                                    style="width: 16px; height: 16px;">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script> 
    
    document.addEventListener('DOMContentLoaded', function() {
        window.history.pushState(null, "", window.location.href);        
        window.onpopstate = function() {
            window.history.pushState(null, "", window.location.href);
        };
    });

    </script>
    <style>
        .card-header {
            text-align: left;
            padding: 1rem;
        }

        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: rgba(210, 27, 33, 0.23);
            color: rgba(241, 65, 108, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 0.5rem;
            border: 4px solid #ffffff;
            box-shadow: 0 0 0 4px rgba(210, 27, 33, 1);
        }


        .avatar span {
            line-height: 1;
        }

        .card-header h5 {
            margin: 0;
        }
    </style>
@endsection
