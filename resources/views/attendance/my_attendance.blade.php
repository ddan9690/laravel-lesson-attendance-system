@extends('layouts.app')

@section('title', 'My Lesson Attendance')

@section('content')
@php
    $currentYear = \App\Models\AcademicYear::where('active', true)->first();
    $currentTerm = \App\Models\Term::where('academic_year_id', $currentYear->id ?? 0)
                                     ->where('active', true)->first();
@endphp

<div class="p-4 md:p-6">

    <!-- Teacher Name as main heading -->
    <h1 class="text-3xl font-bold mb-2 text-green-800">
        {{ $user->name ?? 'Teacher' }}
    </h1>

    <!-- Academic Year / Term as subheading -->
    <h2 class="text-xl font-semibold mb-6 text-gray-700">
        {{ $currentYear->year ?? 'N/A' }} / {{ $currentTerm->name ?? 'N/A' }}
    </h2>

    <div class="bg-white shadow rounded p-4 md:p-6 overflow-x-auto">
        @if(!empty($attendanceData['weeks']) && count($attendanceData['weeks']) > 0)
        <table class="min-w-full table-auto text-sm md:text-base">
            <thead class="bg-green-600 text-white text-xs md:text-sm uppercase">
                <tr>
                    <th class="py-2 px-3 text-left whitespace-nowrap">Week</th>
                    <th class="py-2 px-3 text-left whitespace-nowrap font-bold">Total</th>
                    <th class="py-2 px-3 text-left whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData['weeks'] as $week)
                    <tr class="border-b border-green-200 even:bg-green-50 hover:bg-green-100 transition-colors">
                        <td class="py-2 px-3 whitespace-nowrap">{{ $week['week_name'] }}</td>
                        <td class="py-2 px-3 whitespace-nowrap font-bold">{{ $week['total'] }}</td>
                        <td class="py-2 px-3 whitespace-nowrap">
                            <a href="{{ route('dashboard.weekAttendance', $week['week_id'] ?? 0) }}"
                               class="bg-blue-600 text-white px-3 py-1 rounded shadow hover:bg-blue-700 font-semibold text-sm">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p class="text-gray-500">No attendance data available for the current active term.</p>
        @endif
    </div>

</div>
@endsection
