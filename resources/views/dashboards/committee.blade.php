@extends('layouts.app')

@section('title', 'Committee Dashboard')

@section('content')
<div class="p-3 md:p-6">

    {{-- Academic Context --}}
    @include('partials.academic-context-header')

    {{-- Top Action Buttons --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <a href="{{ route('attendance.create') }}"
           class="bg-green-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-lg shadow hover:bg-green-700 font-semibold text-sm md:text-base transition">
            Capture New Lesson Attendance
        </a>

        <a href="{{ route('dashboard.myAttendance') }}"
           class="bg-blue-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-lg shadow hover:bg-blue-700 font-semibold text-sm md:text-base transition">
            My Lessons
        </a>

        <a href="{{ route('classAttendance') }}"
           class="bg-teal-600 text-white px-3 py-1 md:px-4 md:py-2 rounded-lg shadow hover:bg-teal-700 active:bg-teal-800 font-semibold text-sm md:text-base transition">
            Class Attendance
        </a>
    </div>

    {{-- Filters Panel --}}
    <div x-data="attendanceFilter()"
         class="flex flex-wrap gap-4 mb-3 items-end text-sm md:text-base bg-white shadow rounded-lg p-4">

        {{-- Helper Instructions --}}
        <div class="w-full text-teal-700 text-xs md:text-sm">
            <ol class="list-decimal list-inside space-y-1">
                <li>
                    Filter attendance by date range. The <strong>Download</strong> button generates a PDF
                    for the selected period.
                </li>
                <li>
                    If no date range is selected, all attendance up to today for the term is shown.
                </li>
            </ol>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">From</label>
            <input type="date" x-model="fromDate"
                   class="border rounded px-2 py-1 text-sm md:text-base focus:ring focus:ring-green-200">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">To</label>
            <input type="date" x-model="toDate"
                   class="border rounded px-2 py-1 text-sm md:text-base focus:ring focus:ring-green-200">
        </div>

        <button @click="applyFilters()"
                class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition text-sm md:text-base">
            View
        </button>

        <button @click="resetFilters()"
                class="bg-gray-200 text-gray-800 px-4 py-2 rounded shadow hover:bg-gray-300 transition text-sm md:text-base">
            Reset
        </button>
    </div>

    {{-- Summary Table --}}
    @if ($lessonAttendanceSummary->isNotEmpty())
        <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">

            {{-- Header + Download --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-2">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">
                    Teachers Lesson Attendance Summary
                </h2>

                <a href="{{ route('pdf.attendance.teachers-summary', [
                        'from' => request('from'),
                        'to'   => request('to'),
                    ]) }}"
                   class="inline-flex items-center gap-2 bg-green-600 text-white
                          px-4 py-2 rounded-md shadow hover:bg-green-700 transition
                          text-sm font-medium w-fit">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 16v-8m0 8l-3-3m3 3l3-3m6 5H6" />
                    </svg>
                    Download
                </a>
            </div>

            @php
                $totalCbc = 0;
                $total844 = 0;
                $grandTotal = 0;
            @endphp

            <table class="min-w-full table-fixed text-xs md:text-sm">
                <thead class="bg-green-600 text-white uppercase">
                    <tr>
                        <th class="px-2 py-2 text-left w-10">#</th>
                        <th class="px-2 py-2 text-left">Teacher</th>
                        <th class="px-2 py-2 text-center w-20">CBC</th>
                        <th class="px-2 py-2 text-center w-20">8-4-4</th>
                        <th class="px-2 py-2 text-center w-20">Total</th>
                        <th class="px-2 py-2 text-center w-24">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lessonAttendanceSummary as $index => $summary)
                        @php
                            $totalCbc += $summary['cbc'];
                            $total844 += $summary['eight_four_four'];
                            $grandTotal += $summary['total'];
                        @endphp

                        <tr class="border-b even:bg-green-50 hover:bg-green-100 transition">
                            <td class="px-2 py-1">{{ $index + 1 }}</td>

                            <td class="px-2 py-1 font-medium text-gray-800">
                                {{ $summary['teacher']->name ?? 'N/A' }}
                            </td>

                            <td class="px-2 py-1 text-center">
                                {{ $summary['cbc'] }}
                            </td>

                            <td class="px-2 py-1 text-center">
                                {{ $summary['eight_four_four'] }}
                            </td>

                            <td class="px-2 py-1 text-center font-semibold">
                                {{ $summary['total'] }}
                            </td>

                            <td class="px-2 py-1 text-center">
                                @if($summary['teacher'])
                                    <a href="{{ route('attendance.teacherWeeks', $summary['teacher']->id) }}"
                                       class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                        View
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- FOOTER TOTALS --}}
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="2" class="px-2 py-2 text-right text-gray-800">
                            TOTALS
                        </td>
                        <td class="px-2 py-2 text-center text-green-700">
                            {{ $totalCbc }}
                        </td>
                        <td class="px-2 py-2 text-center text-blue-700">
                            {{ $total844 }}
                        </td>
                        <td class="px-2 py-2 text-center text-black">
                            {{ $grandTotal }}
                        </td>
                        <td class="px-2 py-2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <p class="text-gray-500 mt-4 text-sm">
            No attendance data available for the selected filters.
        </p>
    @endif
</div>

{{-- Alpine.js --}}
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
