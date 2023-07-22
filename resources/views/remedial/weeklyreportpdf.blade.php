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

        h5 {
            text-align: center;
            margin: 0 auto;
            font-size: 20px;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .week-label {
            font-size: 10px; /* Updated font size for WK labels */
        }

        h5,
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
            font-size: 11px; /* Updated font size for column heads */
        }

        table td:last-child {
            font-weight: bold; /* Updated style for the last column (Total) */
        }
        .logo {
            display: block;
            width: 60px; /* Adjust the width of the logo as needed */
            height: auto; /* Maintain aspect ratio */
            margin: 0 auto 10px; /* Center the logo and add some margin at the bottom */
        }
    </style>
</head>

<body>

    <div style="text-align: center;">
        <img src="{{ public_path('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="logo">
    </div>
    <h5>MOI NYABOHANSE GIRLS HIGH SCHOOL</h5>
    <p>WEEKLY ATTENDANCE RECORDS</p>
    <table>
        <thead>
            <tr>
                <th class="week-label"></th>
                <th class="week-label">Name</th>
                @foreach ($weeks as $week)
                    <th class="week-label">WK {{ $week->week_number }}</th>
                @endforeach
                <th class="week-label">TOTAL</th>
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
                            $taughtCount = $user->attendances->where('week_id', $week->id)->count();
                            $userTotal += $taughtCount;
                            $columnTotals[$week->id] = ($columnTotals[$week->id] ?? 0) + $taughtCount;
                        @endphp
                        <td style="text-align: center">{{ $taughtCount }}</td>
                    @endforeach
                    <td style="text-align: center">{{ $userTotal }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
