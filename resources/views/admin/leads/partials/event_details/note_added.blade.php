@php
$payload = json_decode($item->payload, true);
$note = $payload['notes'] ?? null;
@endphp

@if($note)
<p>Notes: {{ $note['note_text'] ?? 'No note text found' }}</p>
{{-- <p>Date: {{ isset($note['created_at']) ? \Carbon\Carbon::parse($note['created_at'])->format('Y-m-d H:i:s') : 'No date found' }}</p> --}}
@else
<p>Notes: No note text found</p>
<p>Date: No date found</p>
@endif
