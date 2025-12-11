<!DOCTYPE html>
<html>
<head>
    <title>Reservations Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Reservations Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Facility</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Recurring</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->user->name }}</td>
                <td>{{ $r->facility->name }}</td>
                <td>{{ $r->start_time }}</td>
                <td>{{ $r->end_time }}</td>
                <td>{{ $r->status }}</td>
                <td>{{ $r->is_recurring ? $r->recurring_type : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
