<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teachers Lesson Attendance Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #111827;
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
        }

        .logo {
            max-width: 50px;
            margin: 0 auto 10px;
            display: block;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1E7D3D;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .sub-heading {
            font-size: 12px;
            font-weight: bold;
            color: #1E7D3D;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .period {
            font-size: 10px;
            margin-bottom: 10px;
            color: #111827;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto 20px auto;
            font-family: Arial, sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid #555;
            font-size: 10px;
        }

        th {
            background-color: transparent;
            color: #1E7D3D;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px;
        }

        td {
            padding: 4px;
            text-align: center;
        }

        tr:nth-child(even) td {
            background-color: #F9FAFB;
        }

        td.total {
            font-weight: bold;
            color: #1E7D3D;
        }

        .timestamp {
            text-align: right;
            font-size: 8px;
            color: #111827;
        }
    </style>
</head>

<body>
    <div class="container">

        @if (file_exists(public_path('remedialsystem/assets/img/logo.png')))
            <img src="{{ public_path('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="logo">
        @endif

        <div class="school-name">MOI NYABOHANSE GIRLS HIGH SCHOOL</div>

        <div class="sub-heading">
            {{ $currentYear->year ?? '' }} @if ($currentTerm)
                — {{ $currentTerm->name }}
            @endif
        </div>

        <div class="period">
            @if (!$fromDate && !$toDate)
                REMEDIAL LESSON ATTENDANCE FOR {{ $currentTerm->name ?? '' }}
            @else
                REMEDIAL LESSON ATTENDANCE FROM
                {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d/m/y') : 'BEGINNING' }}
                TO {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d/m/y') : 'TODAY' }}
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Teacher</th>
                    <th colspan="2">8-4-4</th>
                    <th colspan="2">CBC</th>
                    <th rowspan="2">Total</th>
                </tr>
                <tr>
                    <th>Taught</th>
                    <th>Missed</th>
                    <th>Taught</th>
                    <th>Missed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessonAttendanceSummary as $index => $summary)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left; padding-left: 5px;">
                            {{ strtoupper($summary['teacher']->name ?? 'N/A') }}</td>
                        <td>{{ $summary['eight_four_four_taught'] ?? 0 }}</td>
                        <td>{{ $summary['eight_four_four_missed'] ?? 0 }}</td>
                        <td>{{ $summary['cbc_taught'] ?? 0 }}</td>
                        <td>{{ $summary['cbc_missed'] ?? 0 }}</td>
                        <td class="total">{{ $summary['total_lessons'] ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No attendance data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="timestamp">
            Generated at:
            {{ \Carbon\Carbon::parse($generatedAt)->setTimezone('Africa/Nairobi')->format('d/m/y g:i a') }}
        </div>



    </div>
</body>

</html>
