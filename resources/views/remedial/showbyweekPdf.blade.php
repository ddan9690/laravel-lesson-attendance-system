<!DOCTYPE html>
<html>

<head>
    <title>Remedial</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        table {
            border: 1px solid #333;
            border-collapse: collapse;
            padding: 4px;
            width: 100%;
        }

        table th {
            border: 1px solid #333;
            padding: 4px;
            background: none;
            color: #313030;
            font-weight: bold;
        }

        table td {
            border: 1px solid #333;
            text-align: left;
            padding: 5px;
            background: #ffffff;
            color: #313030;
            font-size: 14px;
        }

        h4, p {
            text-align: center;
            margin: 0;
            line-height: 1;
        }

        table th:first-child,
        table td:first-child {
            width: 20px; /* Adjusted width for the # column to fit numbers */
            text-align: center;
        }

        .logo {
            display: block;
            width: 60px;
            height: auto;
            margin: 0 auto 10px;
        }

        .name-cell {
            white-space: nowrap;
            width: auto; /* This will make the Name column auto-fit */
        }

        /* Set increased width for empty columns */
        .empty-column {
            width: 15%; /* Increased width */
        }

        /* Reduce width of Lessons column */
        .lessons-column {
            width: 5%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div style="text-align: center;">
        <img src="{{ public_path('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="logo">
    </div>
    <h4>MOI NYABOHANSE GIRLS' HIGH SCHOOL</h4>
    <p>REMEDIAL ATTENDANCE FOR WEEK {{ $week->week_number }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th class="lessons-column">Lessons</th>
                <th class="empty-column"></th>
                <th class="empty-column"></th>
                <th class="empty-column"></th>
                <th class="empty-column"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="name-cell">{{ $user->name }}</td>
                    <td class="lessons-column">{{ $user->attendances->count() }}</td>
                    <td class="empty-column"></td>
                    <td class="empty-column"></td>
                    <td class="empty-column"></td>
                    <td class="empty-column"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
