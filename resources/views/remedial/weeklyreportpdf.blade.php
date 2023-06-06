@php
    $attendances = DB::table('attendances')->count();
    $timezone = new DateTimeZone('Africa/Nairobi');
    $currentDateTime = new DateTime('now', $timezone);
    $currentDateTimeFormatted = $currentDateTime->format('d/m/y h:i a');
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Remedial</title>
    <style>
        table {
            border: 1px solid #b3adad;
            border-collapse: collapse;
            padding: 5px;
            width: 100%;
            margin: 0 auto;
            table-layout: auto;
        }

        table th {
            border: 1px solid #b3adad;
            padding: 1px;
            color: #313030;
        }

        table td {
            border: 1px solid #b3adad;
            text-align: left;
            padding: 2px;
            /* reduce the padding */
            background: #ffffff;
            color: #313030;
            font-size: 14px;
            /* reduce the font size */
        }

        h4 {
            text-align: center;
            margin: 0 auto;
            font-size: 20px;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .week-label {
            font-size: 10px; /* Updated font size for WK labels */
        }

        h4,
        p {
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        table th:first-child,
        table td:first-child {
            width: 3%;
        }



        .week-label {
            font-size: 11pxpx; /* Updated font size for column heads */
        }

        .normal-column {
            border-left: 2px solid #080808; /* Thick left border for Normal column */
        }
        table td:nth-last-child(3),
        table td:nth-last-child(2),
        table td:last-child {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h4>MOI NYABOHANSE GIRLS HIGH SCHOOL</h4>
    <p>WEEKLY ATTENDANCE RECORDS</p>
    <table>
        <thead>
            <tr>
                <th class="week-label"></th>
                <th class="week-label">Name</th>
                @foreach ($weeks as $week)
                    <th class="week-label">WK {{ $week->week_number }}</th>
                @endforeach
                <th class="week-label ">Normal</th>
                <th nowrap class="week-label">Mk-Ups</th>
                <th class="week-label">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $columnTotals = [];
            @endphp
            @foreach ($users as $user)
                <tr>
                    <td style="font-size: 10px;">{{ $loop->iteration }}</td>
                    <td style="white-space: nowrap; text-transform: uppercase; font-size: 10px;">{{ $user->name }}</td>
                    @php
                        $userTotal = 0;
                    @endphp
                    @foreach ($weeks as $week)
                        @php
                            $taughtCount = $user->attendances->where('week_id', $week->id)->where('status', '')->count();
                            $makeUpCount = $user->attendances->where('week_id', $week->id)->where('status', 'make-up')->count();
                            $userTotal += $taughtCount;
                            $columnTotals[$week->id] = ($columnTotals[$week->id] ?? 0) + $taughtCount;
                        @endphp
                        <td style="text-align: center">{{ $taughtCount }}</td>
                    @endforeach
                    <td style="text-align: center">{{ $userTotal }}</td>
                    <td style="text-align: center">{{ $user->attendances->where('status', 'make-up')->count() }}</td>
                    <td style="text-align: center">{{ $user->attendances->count() }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>





</body>

</html>
