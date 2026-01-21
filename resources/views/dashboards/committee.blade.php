@extends('layouts.app')

@section('title', 'Committee Dashboard')

@section('content')
    <div class="p-3 md:p-6">

        @include('partials.academic-context-header')

        <!-- Top Action Buttons -->
        <div class="flex flex-wrap gap-3 mb-4">
            <a href="{{ route('attendance.create') }}"
               class="bg-green-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-lg shadow hover:bg-green-700 font-semibold text-sm md:text-base">
                Capture New Lesson Attendance
            </a>

            <a href="{{ route('dashboard.myAttendance') }}"
               class="bg-blue-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-lg shadow hover:bg-blue-700 font-semibold text-sm md:text-base">
                View My Lesson Attendance
            </a>

           <a href="{{ route('classAttendance') }}" class="bg-teal-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-lg shadow hover:bg-teal-700 focus:bg-teal-700 focus:outline-none active:bg-teal-800 font-semibold text-sm md:text-base transition">View Class Attendance</a>

        </div>

        <!-- Filters Panel -->
        <div x-data="attendanceFilter()"
             class="flex flex-wrap gap-3 mb-5 items-end text-sm md:text-base bg-white shadow rounded-lg p-3">

            <div>
                <label class="block text-gray-700 font-medium mb-0.5">From</label>
                <input type="date" x-model="fromDate"
                       class="border rounded px-2 py-1 text-sm md:text-base">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-0.5">To</label>
                <input type="date" x-model="toDate"
                       class="border rounded px-2 py-1 text-sm md:text-base">
            </div>

            <button @click="applyFilters()"
                    class="bg-green-600 text-white px-4 py-1.5 rounded shadow hover:bg-green-700 text-sm md:text-base">
                View
            </button>

            <button @click="resetFilters()"
                    class="bg-gray-200 text-gray-800 px-4 py-1.5 rounded shadow hover:bg-gray-300 text-sm md:text-base">
                Reset
            </button>
        </div>

        <!-- Summary Table -->
        @if ($lessonAttendanceSummary->isNotEmpty())
            <div class="bg-white shadow rounded-lg p-3 overflow-x-auto">

                <!-- Header + PDF Button -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3 gap-2">
                    <h2 class="text-lg md:text-xl font-bold text-gray-800">
                        Teachers Lesson Attendance Summary
                    </h2>

                    <a href="{{ route('pdf.attendance.teachers-summary') }}"
                       class="inline-flex items-center gap-2 bg-green-600 text-white
                              px-3 py-1.5 rounded-md shadow
                              hover:bg-green-700 transition
                              text-sm font-medium w-fit">
                        <!-- Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 16v-8m0 8l-3-3m3 3l3-3m6 5H6" />
                        </svg>
                        Download
                    </a>
                </div>

                <table class="min-w-full table-fixed text-xs md:text-sm">
                    <thead class="bg-green-600 text-white uppercase">
                        <tr>
                            <th class="px-2 py-1 text-left w-10">#</th>
                            <th class="px-2 py-1 text-left">Teacher</th>
                            <th class="px-2 py-1 text-center w-20">CBC</th>
                            <th class="px-2 py-1 text-center w-20">8-4-4</th>
                            <th class="px-2 py-1 text-center w-20">Total</th>
                            <th class="px-2 py-1 text-center w-24">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($lessonAttendanceSummary as $index => $summary)
                            <tr class="border-b even:bg-green-50 hover:bg-green-100">
                                <td class="px-2 py-1">{{ $index + 1 }}</td>
                                <td class="px-2 py-1 font-medium">
                                    {{ $summary['teacher']->name ?? 'N/A' }}
                                </td>
                                <td class="px-2 py-1 text-center">{{ $summary['cbc'] }}</td>
                                <td class="px-2 py-1 text-center">{{ $summary['eight_four_four'] }}</td>
                                <td class="px-2 py-1 text-center font-semibold">{{ $summary['total'] }}</td>
                                <td class="px-2 py-1 text-center">
                                    <a href="{{ route('attendance.teacherWeeks', $summary['teacher']->id) }}"
                                       class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
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
                No attendance data available for selected filters.
            </p>
        @endif
    </div>

    <script>
        function attendanceFilter() {
            return {
                fromDate: '{{ $fromDate ?? '' }}',
                toDate: '{{ $toDate ?? '' }}',

                applyFilters() {
                    const params = new URLSearchParams();
                    if (this.fromDate) params.append('from', this.fromDate);
                    if (this.toDate) params.append('to', this.toDate);

                    window.location.href = `/dashboard/committee?${params.toString()}`;
                },

                resetFilters() {
                    window.location.href = '/dashboard/committee';
                }
            }
        }
    </script>
@endsection
