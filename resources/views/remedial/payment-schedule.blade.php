<!DOCTYPE html>
<html>

<head>
    <title>Remedial</title>
    <style>
        table {
            border: 1px solid #333;
            border-collapse: collapse;
            padding: 4px;
            width: 100%;
            /* Set table to landscape orientation */
            orientation: landscape;
            margin: 0 auto;
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
            margin: 0 auto;
            line-height: 1;
        }

        table th:first-child,
        table td:first-child {
            width: auto;
        }

        .logo {
            display: block;
            width: 60px;
            height: auto;
            margin: 0 auto 10px;
        }

        .name-cell {
            white-space: nowrap;
        }

        /* Set width for empty columns */
        .empty-column {
            width: 10.50%;
        }

        /* Reduce width of Lessons column */
        .lessons-column {
            width: 5%;
            text-align: center;
        }

        /* Adjust width of Sign column */

    </style>
</head>

<body>
    <div style="text-align: center;">
        <img src="{{ public_path('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="logo">
    </div>
    <h4>MOI NYABOHANSE GIRLS HIGH SCHOOL</h4>
    <p>PAYMENT SCHEDULE</p>
    <table>
        <thead>
            <tr>
                <th style="width: auto;">#</th>
                <th>Name</th>
                <th class="lessons-column">Lessons</th>
                <th>Amount</th>
                <th class="empty-column"></th>
                <th class="empty-column"></th>
                <th class="empty-column"></th>
                <th class="empty-column"></th>
                <th style="width: 10%">Total</th>
                <th class="sign-column">Sign</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="name-cell">{{ $user->name }}</td>
                    <td class="lessons-column">{{ $user->attendances->count() }}</td>
                    <td></td>
                    <td class="empty-column"></td>
                    <td class="empty-column"></td>
                    <td class="empty-column"></td>
                    <td class="empty-column"></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
