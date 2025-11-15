@php 
     $timezone = new DateTimeZone('Africa/Nairobi');
    $currentDateTime = new DateTime('now', $timezone);
    $currentDateTimeFormatted = $currentDateTime->format('D, d/m/y - h:i a');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Remedial Payment Slips - {{ $grade->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.25;
            margin: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #444;
            padding: 3px 5px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #eee;
            font-size: 11px;
        }
        td {
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .generation-date {
            font-size: 10px;
            font-style: italic;
            margin-top: 3px;
        }
        .page-break {
            page-break-after: always;
        }
        .stream-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

    @foreach ($streamsData as $streamIndex => $stream)
        <div class="stream-title"> {{ $grade->name . ' ' . $stream['stream_name'] }}</div>

        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:15%;">Adm</th>
                    <th style="width:45%;">Name</th>
                    <th style="width:15%;" class="text-right">Paid</th>
                    <th style="width:15%;" class="text-right">Balance </th>
                </tr>
            </thead>
            <tbody>
                @forelse($stream['students'] as $index => $student)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $student['adm'] }}</td>
                        <td>{{ $student['name'] }}</td>
                        <td class="text-right">{{ number_format($student['total_paid']) }}</td>
                        <td class="text-right">{{ number_format($student['balance']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="generation-date">
            Generated on: {{ $currentDateTimeFormatted }}
        </div>

        @if($streamIndex < count($streamsData) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
