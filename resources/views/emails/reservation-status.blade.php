<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reservation Status</title>
</head>
<body>
    <h2>Reservation Status Update</h2>

    <p>Hello {{ $reservation->user->name }},</p>

    <p>Your reservation request has been <strong>{{ strtoupper($reservation->status) }}</strong>.</p>

    <h3>Reservation Details:</h3>
    <ul>
        <li>Facility: {{ $reservation->facility->name }}</li>
        <li>Event Name: {{ $reservation->event_name }}</li>
        <li>Participants: {{ $reservation->participants }}</li>
        <li>Notes: {{ $reservation->notes }}</li>
        <li>Start: {{ $reservation->start_time }}</li>
        <li>End: {{ $reservation->end_time }}</li>
        <li>Status: {{ $reservation->status }}</li>
    </ul>

    <p>Thank you.</p>
</body>
</html>
