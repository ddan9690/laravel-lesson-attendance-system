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
    </div>

    <!-- Filters Panel -->
    <div x-data="attendanceFilter()" class="flex flex-wrap gap-2 mb-5 items-end text-sm md:text-base bg-white shadow rounded-lg p-3">
        <!-- From Date -->
        <div>
            <label class="block text-gray-700 font-medium mb-0.5">From</label>
            <input type="date" x-model="fromDate"
                   class="border rounded px-2 py-1 text-sm md:text-base">
        </div>

        <!-- To Date -->
        <div>
            <label class="block text-gray-700 font-medium mb-0.5">To</label>
            <input type="date" x-model="toDate"
                   class="border rounded px-2 py-1 text-sm md:text-base">
        </div>

        <!-- Apply Button -->
        <div>
            <button @click="applyFilters()"
                    class="bg-green-600 text-white px-3 py-1 md:px-3 md:py-1 rounded shadow hover:bg-green-700 text-sm md:text-base">
                View
            </button>
        </div>

        <!-- Reset Button -->
        <div>
            <button @click="resetFilters()"
                    class="bg-gray-300 text-gray-800 px-3 py-1 md:px-3 md:py-1 rounded shadow hover:bg-gray-400 text-sm md:text-base">
                Reset
            </button>
        </div>
    </div>

    <!-- Summary Table -->
    @if($lessonAttendanceSummary->isNotEmpty())
        <div class="bg-white shadow rounded-lg p-3 overflow-x-auto">
            <h2 class="text-lg md:text-xl font-bold mb-3 text-gray-800">
                Teachers Lesson Attendance Summary
            </h2>

            <table class="min-w-full table-fixed text-xs md:text-sm">
                <thead class="bg-green-600 text-white uppercase">
                    <tr>
                        <th class="px-2 py-1 text-left w-10 whitespace-nowrap">#</th>
                        <th class="px-2 py-1 text-left whitespace-nowrap">Teacher</th>
                        <th class="px-2 py-1 text-center w-20 whitespace-nowrap">CBC</th>
                        <th class="px-2 py-1 text-center w-20 whitespace-nowrap">8-4-4</th>
                        <th class="px-2 py-1 text-center w-20 whitespace-nowrap">Total</th>
                        <th class="px-2 py-1 text-center w-24 whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($lessonAttendanceSummary as $index => $summary)
                        <tr class="border-b even:bg-green-50 hover:bg-green-100">
                            <td class="px-2 py-1 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-2 py-1 font-medium whitespace-nowrap">{{ $summary['teacher']->name ?? 'N/A' }}</td>
                            <td class="px-2 py-1 text-center whitespace-nowrap">{{ $summary['cbc'] }}</td>
                            <td class="px-2 py-1 text-center whitespace-nowrap">{{ $summary['eight_four_four'] }}</td>
                            <td class="px-2 py-1 text-center font-semibold whitespace-nowrap">{{ $summary['total'] }}</td>
                            <td class="px-2 py-1 text-center whitespace-nowrap">
                                <a href="{{ route('attendance.teacherWeeks', $summary['teacher']->id) }}"
                                   class="bg-blue-500 text-white px-2 py-0.5 md:px-2 md:py-1 rounded text-xs md:text-sm hover:bg-blue-600 whitespace-nowrap">
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
            this.fromDate = '';
            this.toDate = '';
            window.location.href = '/dashboard/committee';
        }
    }
}
</script>
@endsection
