<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        /* Mobile View CSS */
        @media (max-width: 767px) {
            .form-group {
                margin-bottom: 15px;
            }

            .form-control {
                font-size: 14px;
                padding: 10px;
            }

            .card {
                padding: 10px;
            }

            h5 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .submit-btn,
            #addChildBtn {
                width: 100%;
                font-size: 16px;
                padding: 12px;
            }

            /* Reduce padding for the child details container */
            .child-details {
                padding: 10px;
            }

            /* Hide unnecessary long inputs for mobile view */
            input[type="text"],
            input[type="email"],
            textarea {
                font-size: 14px;
                padding: 10px;
            }

            /* Ensure full width of form on mobile */
            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
            }

            .card h5 {
                font-size: 16px;
            }
        }

        /* Desktop View CSS */
        @media (min-width: 768px) {
            .form-container {
                max-width: 400px;
                margin: 0 auto;
                padding: 20px;
                display: flex;
                justify-content: center;
            }

            .form-card {
                width: 100%;
            }

            /* Phone field should appear in the center of the form */
            .phone-field {
                /* display: flex; */
                justify-content: center;
                align-items: center;
                padding: 20px;
            }
        }

        /* Common styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            font-size: 16px;
            padding: 10px;
        }

        .submit-btn,
        #addChildBtn {
            width: 100%;
            font-size: 16px;
            padding: 12px;
        }

        .form-card {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-card">
            <form method="POST" action="{{ route('walkin.updateLead', ['lead_id' => $lead->id]) }}">
                @csrf
                <input type="hidden" name="parent_stage_id" id="parent_stage_id" value="13">
                <!-- Father Details -->
                <strong>Welcome To Qmis</strong>
                <div class="card p-2 mb-2">
                    <h5>Father Details</h5>
                    <div class="form-group">
                        <input type="text" name="father_details[name]" placeholder="Father's Name" id="father_name"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="father_details[phone]" placeholder="Phone" id="father_phone"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="email" name="father_details[email]" placeholder="Email" id="father_email"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="father_details[occupation]" placeholder="Occupation"
                            id="father_email" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="father_details[income]" placeholder="Annual income"
                            id="father_email" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="card p-2 mb-2">
                    <h5>Mother Details</h5>
                    <div class="form-group">
                        <input type="text" name="mother_details[name]" placeholder="Mother's Name" id="mother_name"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="mother_details[phone]" placeholder="Phone" id="mother_phone"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="email" name="mother_details[email]" placeholder="Email" id="mother_email"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="mother_details[occupation]" placeholder="Occupation"
                            id="father_email" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="mother_details[income]" placeholder="Annual income"
                            id="father_email" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="card p-2 mb-2">
                    <h5>Guardian Details</h5>
                    <div class="form-group">
                        <input type="text" name="guardian_details[name]" placeholder="Guardian's Name"
                            id="guardian_name" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="guardian_details[phone]" placeholder="Phone" id="guardian_phone"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="email" name="guardian_details[email]" placeholder="Email" id="guardian_email"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="text" name="guardian_details[occupation]" placeholder="Occupation"
                            id="guardian_phone" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <input type="email" name="guardian_details[income]" placeholder="income"
                            id="guardian_email" class="form-control form-control-sm">
                    </div>
                </div>
                <!-- Guardian Details -->
                <div id="childDetailsContainer">
                    <div class="card p-2 mb-2 child-details">
                        <h5>Child Details</h5>
                        <div class="form-group">
                            <input type="text" name="student_details[0][name]" placeholder="Child Name"
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label><br>
                            <input type="date" name="student_details[0][dob]"
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <select name="student_details[0][grade]" class="form-control grade-select">
                                <option value="">Select Grade</option>
                                <option value="pre kg">Pre KG</option>
                                <option value="kg">LKG</option>
                                <option value="kg">UKG</option>
                                <option value="1">Grade 1</option>
                                <option value="2">Grade 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="student_details[0][old_school]" placeholder="Previous School"
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <input type="text" name="student_details[0][blood_group]" placeholder="Blood Group"
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <textarea name="student_details[0][reason_for_quit]" placeholder="Reason for Quitting"
                                class="form-control form-control-sm" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <textarea name="common_details[0][address]" placeholder="Address" class="form-control form-control-sm"
                                rows="4"></textarea>
                        </div>
                        <!-- Remove Child Button (hidden for the first child card) -->
                        <button type="button" class="btn btn-danger remove-child-btn" style="display: none;">Remove
                            Child</button>
                    </div>
                </div>

                <!-- Add Child Button -->
                <button type="button" class="btn btn-success mb-2" id="addChildBtn">Add Child</button>
                <br><br>
                <input type="hidden" name="project_id" id="project_id" value="1">
                <input type="hidden" name="campaign_name" id="campaign_name" value="qmis">
                <div class="form-group">
                    <select class="form-control form-control-lg" name="source_name" id="sub_source_id">
                        <option value="" selected disabled>Source</option>
                        <option>Parent referral</option>
                        <option>Teacher referral</option>
                        <option>Management referral</option>
                        <option>NewsPaper</option>
                        <option>Flyer</option>
                        <option>Local Resident</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="sub_source_name" placeholder="Sub Source"
                        class="form-control form-control-lg" id="sub_source_name">
                </div>

                <!-- Comments -->
                <div class="form-group">
                    <textarea name="" class="form-control form-control-lg" id="comments" rows="2" placeholder="Comments"></textarea>
                </div>

                <button class="btn btn-primary btn-block btn-lg submit-btn"
                    type="submit">{{ trans('global.save') }}</button>
            </form>
        </div>
    </div>
</body>
<script>
    // JavaScript to add and remove Child Details
    let childIndex = 1; // Track the index of children

    // Add Child Functionality
    document.getElementById('addChildBtn').addEventListener('click', function() {
        // Clone the child details card
        let original = document.querySelector('.child-details');
        let clone = original.cloneNode(true);

        // Update the 'name' attributes to include the correct index
        clone.querySelectorAll('input, select, textarea').forEach(function(input) {
            let name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, '[' + childIndex + ']'));
            }
            input.value = ''; // Clear the values
        });

        // Show the Remove button for the cloned card
        clone.querySelector('.remove-child-btn').style.display = 'inline-block';

        // Attach a Remove Child Button to the cloned card
        clone.querySelector('.remove-child-btn').addEventListener('click', function() {
            clone.remove(); // Remove the child card
        });

        // Append the cloned child details card
        document.getElementById('childDetailsContainer').appendChild(clone);

        // Increment the child index
        childIndex++;
    });

    // Remove the original child card (if needed)
    document.querySelector('.remove-child-btn').addEventListener('click', function() {
        this.closest('.child-details').remove();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('update-lead-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting the traditional way

            const formData = new FormData(form);

            fetch('/update-lead/' + form.dataset.leadId, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const messageContainer = document.createElement('div');
                        messageContainer.style.padding = '10px';
                        messageContainer.style.backgroundColor = '#d4edda';
                        messageContainer.style.color = '#155724';
                        messageContainer.style.border = '1px solid #c3e6cb';
                        messageContainer.style.borderRadius = '5px';
                        messageContainer.style.fontSize = '16px';
                        messageContainer.style.position = 'fixed';
                        messageContainer.style.bottom = '20px';
                        messageContainer.style.left = '50%';
                        messageContainer.style.transform = 'translateX(-50%)';
                        messageContainer.style.zIndex = '1000';
                        messageContainer.innerHTML = data.message;

                        document.body.appendChild(messageContainer);

                        // Auto-hide the message after 3 seconds
                        setTimeout(() => {
                            messageContainer.style.display = 'none';
                        }, 3000);
                    } else {
                        alert(data.message); // Handle error case
                    }
                });
        });
    });
</script>
