@extends('layouts.app')

@section('title', 'Committee Dashboard')

@section('content')
<div class="p-4 md:p-6">

    <!-- Top: Capture Attendance -->
    <div class="flex justify-start mb-4 md:mb-6">
        <a href="{{ route('attendance.create') }}"
           class="bg-green-600 text-white px-4 py-2 md:px-6 md:py-3 rounded-xl shadow hover:bg-green-700 font-semibold text-sm md:text-base">
           Capture New Lesson Attendance
        </a>
    </div>

    <!-- Teacher Attendance Table -->
    <div class="bg-white shadow rounded p-4 md:p-6 overflow-x-auto">
        <h2 class="text-xl font-semibold mb-4">Teacher Attendance Overview</h2>

        @if(!empty($attendanceAnalysis) && count($attendanceAnalysis) > 0)
        <table class="min-w-full table-auto text-sm md:text-base">
            <thead class="bg-green-600 text-white text-xs md:text-sm uppercase">
                <tr>
                    <th class="py-2 px-3 text-left whitespace-nowrap">#</th>
                    <th class="py-2 px-3 text-left whitespace-nowrap">Teacher</th>
                    <th class="py-2 px-3 text-left whitespace-nowrap">8-4-4</th>
                    <th class="py-2 px-3 text-left whitespace-nowrap">CBC</th>
                    <th class="py-2 px-3 text-left whitespace-nowrap font-bold">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceAnalysis as $index => $teacher)
                    <tr class="border-b border-green-200 even:bg-green-50 hover:bg-green-100 transition-colors">
                        <td class="py-2 px-3 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="py-2 px-3 whitespace-nowrap">{{ $teacher['name'] }}</td>
                        <td class="py-2 px-3 whitespace-nowrap">{{ $teacher['attendance_by_curriculum']['8-4-4'] }}</td>
                        <td class="py-2 px-3 whitespace-nowrap">{{ $teacher['attendance_by_curriculum']['CBC'] }}</td>
                        <td class="py-2 px-3 whitespace-nowrap font-bold">{{ $teacher['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-gray-500">No attendance data available.</p>
        @endif
    </div>

</div>
@endsection
