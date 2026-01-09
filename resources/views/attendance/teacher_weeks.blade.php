@extends('layouts.app')

@section('title', 'Teacher Weeks Attendance')

@section('content')
    <div class="p-4 md:p-6">

        <!-- Header: Teacher & Current Context -->
        <h1 class="text-2xl font-bold text-green-800 mb-2">
            {{ $teacher->name ?? 'Teacher' }}
        </h1>

        <h2 class="text-lg text-gray-700 mb-4">
            {{ $currentYear->year ?? 'N/A' }}
            : {{ $currentTerm->name ?? 'N/A' }}
        </h2>

        @if ($weeks->isNotEmpty())
            <div class="bg-white shadow rounded-lg p-3 overflow-x-auto">
                <table class="min-w-full table-fixed text-sm md:text-base">
                    <thead class="bg-green-600 text-white uppercase">
                        <tr>
                            <th class="px-2 py-1 text-left">Week</th>
                            <th class="px-2 py-1 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weeks as $week)
                            <tr class="border-b border-green-200 even:bg-green-50 hover:bg-green-100">
                                <td class="px-2 py-1 text-left whitespace-nowrap">{{ $week->name }}</td>
                                <td class="px-2 py-1 text-center whitespace-nowrap">
                                    <a href="{{ route('attendance.teacherWeekAttendance', [$teacher->id, $week->id]) }}"
                                        class="bg-blue-500 text-white px-2 py-1 rounded shadow hover:bg-blue-600 text-xs">
                                        View Attendance
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
