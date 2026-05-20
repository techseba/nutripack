@props([
    'pageName'  =>   'Kitchen Report',
])
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $pageName }} - {{ $date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 12px; }
        .meta { font-size: 11px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid gray; padding: 6px 8px; vertical-align: top; }
        th { background: #f4f4f4; font-weight: 600; text-align: left; }
        .small { font-size: 11px; color: #555; }
        .page-name { position: fixed; bottom: 0; left: 0; right: 0; text-align: left; font-size: 10px; color: #666; }
        @page { margin: 20mm 10mm; }
        footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: right; font-size: 10px; color: #666; }
        @page { margin: 20mm 10mm; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $pageName }}</h2>
        <div class="meta">Date: <strong>{{ \Carbon\Carbon::parse($date)->setTimezone(config('app.timezone'))->format('d M Y') }}</strong></div>
        <div class="meta small">Generated at: {{ $generated_at->setTimezone(config('app.timezone'))->format('d M Y, h:i A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th style="width:90px">Date</th>
                <th>Meal Name</th>
                <th style="width:40px">Quantity</th>
                <th style="width:90px">Meal Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->date)->setTimezone(config('app.timezone'))->format('d M Y') }}</td>
                    <td>{{ ucfirst(str_replace('-', '_', $row->meal_name)) }}</td>
                    <td>{{ $row->qty }}</td>
                    <td>{{ $row->meal_type_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-name">{{ $pageName }}</div>
    <footer>Printed by {{ auth()->user()->name ?? 'System' }}</footer>
</body>
</html>
