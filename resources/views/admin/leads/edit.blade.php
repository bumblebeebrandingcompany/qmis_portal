@extends('layouts.admin')

@section('content')
    <h2>Edit Lead</h2>
    <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="parent_stage_id" id="parent_stage_id" value="13">

        <!-- Father Details -->
        <strong>Welcome To Qmis</strong>
        <div class="card p-2 mb-2">
            <h5>Father Details</h5>
            <div class="form-group">
                <input type="text" name="father_details[name]" placeholder="Father's Name" id="father_name" class="form-control form-control-sm" value="{{ old('father_details.name', $lead->father_details['name'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="father_details[phone]" placeholder="Phone" id="father_phone" class="form-control form-control-sm" value="{{ old('father_details.phone', $lead->father_details['phone'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="email" name="father_details[email]" placeholder="Email" id="father_email" class="form-control form-control-sm" value="{{ old('father_details.email', $lead->father_details['email'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="father_details[occupation]" placeholder="Occupation" id="father_occupation" class="form-control form-control-sm" value="{{ old('father_details.occupation', $lead->father_details['occupation'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="father_details[income]" placeholder="Annual income" id="father_income" class="form-control form-control-sm" value="{{ old('father_details.income', $lead->father_details['income'] ?? '') }}">
            </div>
        </div>

        <!-- Mother Details -->
        <div class="card p-2 mb-2">
            <h5>Mother Details</h5>
            <div class="form-group">
                <input type="text" name="mother_details[name]" placeholder="Mother's Name" id="mother_name" class="form-control form-control-sm" value="{{ old('mother_details.name', $lead->mother_details['name'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="mother_details[phone]" placeholder="Phone" id="mother_phone" class="form-control form-control-sm" value="{{ old('mother_details.phone', $lead->mother_details['phone'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="email" name="mother_details[email]" placeholder="Email" id="mother_email" class="form-control form-control-sm" value="{{ old('mother_details.email', $lead->mother_details['email'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="mother_details[occupation]" placeholder="Occupation" id="mother_occupation" class="form-control form-control-sm" value="{{ old('mother_details.occupation', $lead->mother_details['occupation'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="mother_details[income]" placeholder="Annual income" id="mother_income" class="form-control form-control-sm" value="{{ old('mother_details.income', $lead->mother_details['income'] ?? '') }}">
            </div>
        </div>

        <!-- Guardian Details -->
        <div class="card p-2 mb-2">
            <h5>Guardian Details</h5>
            <div class="form-group">
                <input type="text" name="guardian_details[name]" placeholder="Guardian's Name" id="guardian_name" class="form-control form-control-sm" value="{{ old('guardian_details.name', $lead->guardian_details['name'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="guardian_details[phone]" placeholder="Phone" id="guardian_phone" class="form-control form-control-sm" value="{{ old('guardian_details.phone', $lead->guardian_details['phone'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="email" name="guardian_details[email]" placeholder="Email" id="guardian_email" class="form-control form-control-sm" value="{{ old('guardian_details.email', $lead->guardian_details['email'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="guardian_details[occupation]" placeholder="Occupation" id="guardian_occupation" class="form-control form-control-sm" value="{{ old('guardian_details.occupation', $lead->guardian_details['occupation'] ?? '') }}">
            </div>
            <div class="form-group">
                <input type="text" name="guardian_details[income]" placeholder="Annual income" id="guardian_income" class="form-control form-control-sm" value="{{ old('guardian_details.income', $lead->guardian_details['income'] ?? '') }}">
            </div>
        </div>
        <div class="card p-2 mb-2">
            <h5>Common details</h5>
            <div class="form-group">
                <input type="text" name="common_details[address]" placeholder="address" id="common_details" class="form-control form-control-sm" value="{{ old('common_details.address', $lead->common_details['address'] ?? '') }}">
            </div>
        </div>
        <!-- Child Details -->
        <div id="childDetailsContainer">
            @if(is_array($lead->student_details) && count($lead->student_details) > 0)
                @foreach ($lead->student_details as $index => $child)
                <div class="card p-2 mb-2 child-details">
                    <h5>Child Details</h5>
                    <div class="form-group">
                        <input type="text" name="student_details[{{ $index }}][name]" placeholder="Child Name" class="form-control form-control-sm" value="{{ old('student_details.' . $index . '.name', $child['name'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label><br>
                        <input type="date" name="student_details[{{ $index }}][dob]" class="form-control form-control-sm" value="{{ old('student_details.' . $index . '.dob', $child['dob'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <select name="student_details[{{ $index }}][grade]" class="form-control grade-select">
                            <option value="">Select Grade</option>
                            <option value="pre kg" {{ old('student_details.' . $index . '.grade', $child['grade'] ?? '') == 'pre kg' ? 'selected' : '' }}>Pre KG</option>
                            <option value="kg" {{ old('student_details.' . $index . '.grade', $child['grade'] ?? '') == 'kg' ? 'selected' : '' }}>KG</option>
                            <option value="1" {{ old('student_details.' . $index . '.grade', $child['grade'] ?? '') == '1' ? 'selected' : '' }}>Grade 1</option>
                            <option value="2" {{ old('student_details.' . $index . '.grade', $child['grade'] ?? '') == '2' ? 'selected' : '' }}>Grade 2</option>
                        </select>
                    </div>
                </div>
                @endforeach
            @else
                <p>No child details available.</p>
            @endif
        </div>



        <!-- Add Child Button -->
        {{-- <button type="button" class="btn btn-success mb-2" id="addChildBtn">Add Child</button> --}}

        <!-- Source and Subsource -->
        {{-- <div class="form-group">
            <select class="form-control form-control-lg" name="source_name" id="sub_source_id">
                <option value="" selected disabled>Source</option>
                <option value="Parent referral" {{ old('source_name', $lead->source_name ?? '') == 'Parent referral' ? 'selected' : '' }}>Parent referral</option>
                <option value="Teacher referral" {{ old('source_name', $lead->source_name ?? '') == 'Teacher referral' ? 'selected' : '' }}>Teacher referral</option>
                <option value="Management referral" {{ old('source_name', $lead->source_name ?? '') == 'Management referral' ? 'selected' : '' }}>Management referral</option>
            </select>
        </div>
        <div class="form-group">
            <input type="text" name="sub_source_name" placeholder="Sub Source" class="form-control form-control-lg" id="sub_source_name" value="{{ old('sub_source_name', $lead->sub_source_name ?? '') }}">
        </div> --}}
        {{-- <input type="hidden" name="project_id" id="project_id" value="48">
        <input type="hidden" name="campaign_name" id="campaign_name" value="qmis"> --}}
        <!-- Comments -->
        <div class="form-group">
            <textarea name="comments" class="form-control form-control-lg" id="comments" rows="2" placeholder="Comments">{{ old('comments', $lead->comments ?? '') }}</textarea>
        </div>
        <button class="btn btn-primary" type="submit">Save Lead</button>
    </form>

@endsection
