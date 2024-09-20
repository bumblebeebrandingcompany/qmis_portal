<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Visit Schedule</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-slot {
            display: inline-block;
            width: calc(33.33% - 10px);
            /* Adjust the width of each card */
            margin: 5px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            padding: 15px;
            background-color: #f1f1f1;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .card-slot.selected {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        #time_slots_container {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Schedule Site Visit</h1>
        <form action="{{ route('client.storeSiteVisit', ['id' => $lead->id]) }}" method="POST">

            @csrf
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="form-group">
                <div id="time_slots_container">
                    <label>Time Slot:</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($timeSlots as $slot)
                            <div class="card-slot" data-value="{{ $slot }}">{{ $slot }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" id="selected_time_slot" name="time_slot" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Schedule Visit</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date');
            const timeSlotsContainer = document.getElementById('time_slots_container');
            const cards = document.querySelectorAll('.card-slot');
            const selectedTimeSlotInput = document.getElementById('selected_time_slot');

            dateInput.addEventListener('change', function() {
                if (dateInput.value) {
                    timeSlotsContainer.style.display = 'block';
                } else {
                    timeSlotsContainer.style.display = 'none';
                }
            });

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    cards.forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                    selectedTimeSlotInput.value = card.getAttribute('data-value');
                });
            });
        });
    </script>
</body>

</html>
