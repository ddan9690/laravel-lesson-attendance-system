<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments Report</title>
    <style>
        /* Custom CSS styles to create a stripped table appearance */
        body {
            font-size: 12px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ccc; /* Add border to the table */
        }
        th, td {
            padding: 0.25rem;
            text-align: left;
            font-size: 12px;
            border: 1px solid #ccc; /* Add border to th and td elements */
        }
        th {
            background-color: #f8f9fa;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        h5 {
            font-size: 16px;
        }
        p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h5>Weekly Notices</h5>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedComments as $weekNumber => $comments)
                        <tr>
                            <td>Week {{ $weekNumber }}</td>
                            <td>
                                @foreach($comments as $comment)
                                    <p class="mb-0">-{{ $comment->comment }}</p>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
