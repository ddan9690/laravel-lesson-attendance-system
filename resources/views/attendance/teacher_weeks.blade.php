@extends('layouts.app')

@section('title', 'Teacher Weeks Attendance')

@section('content')
<div class="p-4 md:p-6">

    <!-- Header: Teacher & Current Context -->
    <h1 class="text-2xl font-bold text-green-800 mb-2">
        {{ $teacher->name ?? 'Teacher' }}
    </h1>

    <h2 class="text-lg text-gray-700 mb-4">
        Academic Year: {{ $currentYear->year ?? 'N/A' }} | Term: {{ $currentTerm->name ?? 'N/A' }}
    </h2>

    @if ($weeks->isNotEmpty())
    <div class="bg-white shadow rounded-lg p-3 overflow-x-auto">
        <table class="min-w-full table-fixed text-sm md:text-base border-collapse border border-green-200">
            <thead class="bg-green-600 text-white uppercase">
                <tr>
                    <th rowspan="2" class="px-2 py-1 text-left border border-green-200">WK</th>
                    <th colspan="2" class="px-2 py-1 text-center border border-green-200">8-4-4</th>
                    <th colspan="2" class="px-2 py-1 text-center border border-green-200">CBC</th>
                    <th rowspan="2" class="px-2 py-1 text-center border border-green-200">Actions</th>
                </tr>
                <tr>
                    <th class="px-2 py-1 text-center border border-green-200">Taught</th>
                    <th class="px-2 py-1 text-center border border-green-200">Missed</th>
                    <th class="px-2 py-1 text-center border border-green-200">Taught</th>
                    <th class="px-2 py-1 text-center border border-green-200">Missed</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weeks as $week)
                <tr class="border-b border-green-200 even:bg-green-50 hover:bg-green-100">
                    {{-- Week name in bold --}}
                    <td class="px-2 py-1 text-left whitespace-nowrap border border-green-200 font-bold">
                        {{ $week->name }}
                    </td>
                    
                    {{-- 8-4-4 --}}
                    <td class="px-2 py-1 text-center border border-green-200">
                        {{ $weekAttendance[$week->id]['8-4-4']['attended'] ?? 0 }}
                    </td>
                    <td class="px-2 py-1 text-center border border-green-200">
                        {{ $weekAttendance[$week->id]['8-4-4']['missed'] ?? 0 }}
                    </td>

                    {{-- CBC --}}
                    <td class="px-2 py-1 text-center border border-green-200">
                        {{ $weekAttendance[$week->id]['CBC']['attended'] ?? 0 }}
                    </td>
                    <td class="px-2 py-1 text-center border border-green-200">
                        {{ $weekAttendance[$week->id]['CBC']['missed'] ?? 0 }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-2 py-1 text-center whitespace-nowrap border border-green-200">
                        <a href="{{ route('attendance.teacherWeekAttendance', [$teacher->id, $week->id]) }}"
                            class="bg-blue-500 text-white px-2 py-1 rounded shadow hover:bg-blue-600 text-xs">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-gray-500 mt-4 text-sm">
        No weeks found for the current active term.
    </p>
    @endif

</div>
@endsection
