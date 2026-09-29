<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guest Registrations Export</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        .meta { color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d0d0d0; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Guest Registrations Report</h1>
    <div class="meta">Generated: {{ $generatedAt }}</div>
    <table>
        <thead>
            <tr>
                <th>Registration No</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $guest)
                <tr>
                    <td>{{ $guest->registration_number }}</td>
                    <td>{{ $guest->full_name }}</td>
                    <td>{{ $guest->email ?: '—' }}</td>
                    <td>{{ $guest->phone ?: '—' }}</td>
                    <td>{{ ucfirst($guest->status) }}</td>
                    <td>{{ $guest->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
