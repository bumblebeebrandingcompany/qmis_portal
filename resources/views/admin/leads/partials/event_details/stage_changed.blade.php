@php
    $payload = json_decode($item->payload, true);
    $note = $payload['followup'] ?? null;
    $notetext = $payload['notes'] ?? null;
    $notesitevisit = $payload['sitevisit'] ?? null;
    $noteapplication = $payload['application'] ?? null;
    $noteadmission = $payload['admission'] ?? null;

@endphp

<p>Notes: {{ $note['notes'] ?? '' }}</p>
<p>{{ $notetext['note_text'] ?? '' }}</p>
<p>{{ $notesitevisit['notes'] ?? '' }}</p>
<p>{{ $noteapplication['notes'] ?? '' }}</p>
<p>{{ $noteadmission['notes'] ?? '' }}</p>

