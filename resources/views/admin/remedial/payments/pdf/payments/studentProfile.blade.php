@php 
    $timezone = new DateTimeZone('Africa/Nairobi');
    $currentDateTime = new DateTime('now', $timezone);
    $generated = $currentDateTime->format('D, d/m/Y - h:i A');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Payment Slip - {{ $student->name }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        .header-box {
            text-align: center;
            margin-bottom: 20px;
        }

        .school-name {
            font-size: 22px;
            font-weight: bold;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 6px;
            color: #004080;
        }

        .student-info, .summary-box {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .student-info table, .summary-box table, .payments table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info th, .student-info td,
        .summary-box th, .summary-box td,
        .payments th, .payments td {
            padding: 6px 8px;
            font-size: 12px;
            border: 1px solid #ccc;
        }

        .student-info th {
            text-align: left;
            background: #f5f5f5;
        }

        .summary-box td {
            font-weight: bold;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .italic { font-style: italic; font-size: 11px; }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #004080;
        }

        .payments table th {
            background-color: #f0f0f0;
        }

        .generated-date {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #555;
        }
    </style>
</head>

<body>

<div class="header-box">
    <div class="school-name">MOI NYABOHANSE GIRLS HIGH SCHOOL</div>
</div>

<div class="student-info">
    <table>
        <tr>
            <th>Student Name</th>
            <td>{{ $student->name }}</td>
            <th>ADM No.</th>
            <td>{{ $student->adm }}</td>
        </tr>
        <tr>
            <th>Grade</th>
            <td>{{ $student->grade->name ?? 'N/A' }}</td>
            <th>Stream</th>
            <td>{{ $student->gradeStream->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Admitted</th>
            <td colspan="3">
                {{ $student->joinedAcademicYear->year ?? 'N/A' }} - {{ $student->joinedTerm->name ?? 'N/A' }}
            </td>
        </tr>
    </table>
</div>

<div class="summary-box">
    <table>
        <tr>
            <td>Total Paid</td>
            <td class="text-right">Ksh {{ number_format($totalPaid) }}</td>
        </tr>
        <tr>
            <td>Balance</td>
            <td class="text-right">Ksh {{ number_format($balance) }}</td>
        </tr>
    </table>
</div>

<div class="payments">
    <div class="section-title">Payment History</div>

    @if ($payments->isEmpty())
        <p style="color:red; font-weight:bold;">No payments have been recorded.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-right">Amount (Ksh)</th>
                    <th>Payment Type</th>
                    <th>Transaction No.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $pay)
                    <tr>
                        <td>{{ $pay->created_at->format('d M Y') }}</td>
                        <td class="text-right">{{ number_format($pay->amount) }}</td>
                        <td>{{ strtoupper($pay->payment_type) }}</td>
                        <td>
                            @if($pay->payment_type === 'mpesa')
                                {{ $pay->mpesa_transaction_number ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="generated-date">
  {{ $generated }}.
</div>

</body>
</html>
