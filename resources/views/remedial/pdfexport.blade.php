@php
    $attednaces = DB::table('attendances')->count();
    $timezone = new DateTimeZone('Africa/Nairobi');
    $currentDateTime = new DateTime('now', $timezone);
    $currentDateTimeFormatted = $currentDateTime->format('D, d/m/y - h:i a');
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
            width: 60%;
            margin: 0 auto;

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

        h4, p {
        margin: 0;
        padding: 0;
        line-height: 1;
    }

        table th:first-child,
        table td:first-child {
            width: 5%;
        }

        /* table th:nth-child(2),
        table td:nth-child(2) {
            width: 50%;
        }

        table th:last-child,
        table td:last-child {
            width: 30%;
        } */

        .logo {
            display: block;
            width: 60px; /* Adjust the width of the logo as needed */
            height: auto; /* Maintain aspect ratio */
            margin: 0 auto 10px; /* Center the logo and add some margin at the bottom */
        }

        .notice {
            text-align: center;
            font-size: 16px;
            color: red;
            text-transform: uppercase;
            margin-top: 10px;
        }

        a {
        font-size: 18px;  
        font-weight: bold;
        color: #007bff;
        text-decoration: none; 
        padding: 5px 10px; 
        border-radius: 4px; 
        background-color: #f8f9fa; 
        transition: background-color 0.3s ease; 
    }

    a:hover {
        background-color: #e2e6ea; 
        text-decoration: underline; 
    }
    </style>
</head>

<body>
    <div style="text-align: center;">
        <img src="{{ public_path('remedialsystem/assets/img/logo.png') }}" alt="Logo" class="logo">
    </div>
    <h4>MOI NYABOHANSE GIRLS HIGH SCHOOL</h4>
    <p>REMEDIAL ATTENDANCE RECORDS</p>
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
    <p style="font-weight: bold; font-style: italic; font-size: small;">Records as at <?= $currentDateTimeFormatted ?></p>

    <p style="text-align: center; margin-top: 20px;">
        To view your lessons, please click this link to login:
        <a href="https://remedial.apptempest.com/remedial/attendance/mylessons">https://remedial.apptempest.com/remedial/attendance/mylessons</a>
    </p>

</body>

</html>
