<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Week;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminPDFExport extends Controller
{
    public function AllLessonCountExport()
    {
        $users = User::orderBy('name', 'asc')->get();

        $data = [
            'users' => $users,
        ];

        $pdf = Pdf::loadView('remedial.pdfexport', $data);

        $filename = 'Remedial_' . now()->format('H_i_s-Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function WeeklyAttendanceReportExport()
    {
        $users = User::orderBy('name', 'asc')->get();
        $weeks = Week::orderBy('week_number', 'asc')->get();

        $data = [
            'users' => $users,
            'weeks' => $weeks,
        ];

        $pdf = PDF::loadView('remedial.weeklyreportpdf', $data);

        return $pdf->stream('weekly_report.pdf');
    }

    public function PaymentSchedule()
    {
        $users = User::orderBy('name', 'asc')->get();

        $data = [

            'users' => $users,
        ];

        $pdf = Pdf::loadView('remedial.payment-schedule', $data);

        return $pdf->stream('payment-schedule.pdf');
    }

}
