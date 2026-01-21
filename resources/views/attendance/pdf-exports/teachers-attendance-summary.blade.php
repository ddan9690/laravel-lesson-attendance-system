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
        }
        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin: 0 auto 10px;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }
        table, th, td {
            border: 1px solid #000;
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            text-transform: uppercase;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        th, td {
            white-space: nowrap;
        }
        .header-text {
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            font-family: Arial, sans-serif;
            margin-bottom: 10px;
        }
        td.total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">

        @if(file_exists(public_path('backend/img/logo/cyberspace-national-joint-logo.png')))
            <img src="{{ public_path('backend/img/logo/cyberspace-national-joint-logo.png') }}" alt="Logo" class="logo">
        @endif

        <div class="header-text">
            TEACHERS LESSON ATTENDANCE SUMMARY
            <div>
                {{ $currentYear->name ?? '' }}
                @if($currentTerm)
                    — {{ $currentTerm->name }}
                @endif
                @if($fromDate || $toDate)
                    <br>Period: {{ $fromDate ?? 'BEGINNING' }} TO {{ $toDate ?? 'TODAY' }}
                @endif
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher Name</th>
                    <th>CBC Lessons</th>
                    <th>8-4-4 Lessons</th>
                    <th>Total Lessons</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lessonAttendanceSummary as $index => $summary)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ strtoupper($summary['teacher']->name ?? 'N/A') }}</td>
                    <td>{{ $summary['cbc'] }}</td>
                    <td>{{ $summary['eight_four_four'] }}</td>
                    <td class="total">{{ $summary['total'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No attendance data available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</body>
</html>
