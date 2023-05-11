@php
    
    $attednaces = DB::table('attendances')->count();
@endphp


<!DOCTYPE html>
<html>

<head>
    <title>Remdedial</title>
    <style>
        table {
            border: 1px solid #b3adad;
            border-collapse: collapse;
            padding: 5px;
            width: 70%;
            margin: 0 auto
        }

        table th {
            border: 1px solid #b3adad;
            padding: 1px;
            background: #bdf4ce;
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
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            font-size: 16px;
            margin-bottom: 10px;
        }

        table th:first-child,
        table td:first-child {
            width: 5%;
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 50%;
        }

        table th:last-child,
        table td:last-child {
            width: 30%;
        }
    </style>
</head>

<body>
    <h4>MOI NYABOHANSE GIRLS HIGH SCHOOL</h4>
    <p>TERM 2-2023 REMEDIAL ATTENDANCE RECORDS</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>No. of Lessons</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td style="text-align: center">{{ $user->attendances->count() }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: center"><strong>Total: {{ $attednaces }}</strong></td>

            </tr>
        </tbody>
    </table>
</body>

</html>
