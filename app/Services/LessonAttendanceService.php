<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;

class LessonAttendanceService
{
    /**
     * Get all teacher attendance for committee dashboard
     */
    public function getCommitteeAttendance()
    {
        // Get all teachers except the system admin
        $teachers = User::where('email', '!=', 'dancanokeyo08@gmail.com')
                        ->orderBy('name')
                        ->get();

        $result = [];

        foreach ($teachers as $teacher) {
            // Count attended per curriculum
            $attended844 = Attendance::where('teacher_id', $teacher->id)
                ->where('status', 'attended')
                ->whereHas('curriculum', function($q) {
                    $q->where('name', '8-4-4');
                })->count();

            $attendedCBC = Attendance::where('teacher_id', $teacher->id)
                ->where('status', 'attended')
                ->whereHas('curriculum', function($q) {
                    $q->where('name', 'CBC');
                })->count();

            $result[] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'attendance_by_curriculum' => [
                    '8-4-4' => $attended844,
                    'CBC' => $attendedCBC,
                ],
                'total' => $attended844 + $attendedCBC,
            ];
        }

        return $result;
    }
}
