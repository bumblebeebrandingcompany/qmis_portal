@extends('layouts.admin')

@section('content')
<h2>Project Based Leads</h2>
    <div id="project-list" class="card-container">
        @foreach ($projects as $project)
            <div class="card_new" onclick="window.location.href='{{ route('admin.leads.projects', ['view' => 'kanban', 'id' => $project->id]) }}';">
                <div class="card_new-content">
                    Name: {{ optional($project)->name }} <!-- Use optional() for null safety -->
                </div>
                <div class="popup">
                    Pick Here
                </div>
            </div>
        @endforeach
    </div>
@endsection

<style>
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Space between cards */
}

.card_new {
    flex: 1 1 calc(28.33% - 20px); /* 33.33% width for 3 cards per row, minus gap */
    box-sizing: border-box; /* Ensure padding doesn't affect the width */
    padding: 50px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
}
.card_new:hover {
    background-color: #CEB2FC; /* Hover color */
    transform: translateY(-5px);
}

.card_new-content {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px;
}

.popup {
    margin-top: auto;
    padding: 10px;
    background-color: #ddd;
    text-align: center;
    border-radius: 4px;
    font-size: 14px;
}

    </style>
