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
            /* text-dark */
        }

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
        }

        .logo {
            max-width: 50px;
            /* reduced logo size */
            margin: 0 auto 10px;
            display: block;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1E7D3D;
            /* school-green */
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .sub-heading {
            font-size: 12px;
            font-weight: bold;
            color: #1E7D3D;
            /* school-green */
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
            /* neutral gray for print */
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
            /* subtle for digital */
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

        {{-- School Logo --}}
        @if (file_exists(public_path('remedialsystem/assets/img/logo.png')))
            <img src="{{ public_path('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="logo">
        @endif

        {{-- School Name --}}
        <div class="school-name">
            MOI NYABOHANSE GIRLS HIGH SCHOOL
        </div>

        {{-- Academic Year & Term --}}
        <div class="sub-heading">
            {{ $currentYear->year ?? '' }}
            @if ($currentTerm)
                — {{ $currentTerm->name }}
            @endif
        </div>

        {{-- Period --}}
        <div class="period">
            @if (!$fromDate && !$toDate)
                REMEDIAL LESSON ATTENDANCE FOR {{ $currentTerm->name ?? '' }}
            @else
                REMEDIAL LESSON ATTENDANCE FROM
                {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d/m/y') : 'BEGINNING' }}
                TO {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d/m/y') : 'TODAY' }}
            @endif
        </div>

        {{-- Attendance Table --}}
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
                            {{ strtoupper($summary['teacher']->name ?? 'N/A') }}
                        </td>
                        <td>{{ $summary['eight_four_four_taught'] }}</td>
                        <td>{{ $summary['eight_four_four_missed'] }}</td>
                        <td>{{ $summary['cbc_taught'] }}</td>
                        <td>{{ $summary['cbc_missed'] }}</td>
                        <td class="total" style="font-weight: bold; color: #000;">
                            {{ $summary['total_lessons'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No attendance data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Generated timestamp --}}
        <div class="timestamp">
            Generated at: {{ \Carbon\Carbon::parse($generatedAt)->format('d/m/y H:i') }}
        </div>

    </div>
</body>

</html>
