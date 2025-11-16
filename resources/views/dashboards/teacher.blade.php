@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')

<h1 class="text-2xl font-bold text-school-green mb-6">
    Lesson Attendance – This Week
</h1>

{{-- Summary Card --}}
<div class="bg-white shadow rounded p-4 mb-6">
    <p class="text-lg font-semibold text-school-green">Summary</p>
    <p class="text-gray-700 text-sm mt-1">
        Total Lessons Missed This Week:
        <span class="font-bold text-red-600">3</span>
    </p>
</div>

{{-- 8-4-4 SUBJECTS TABLE --}}
<div class="bg-white shadow rounded p-4 mb-10 overflow-x-auto">
    <h2 class="text-lg font-semibold text-gray-700 mb-3">8-4-4 Attendance</h2>

    <table class="min-w-full border border-gray-200 table-auto whitespace-nowrap text-sm">
        <thead class="bg-school-green text-white text-xs">
            <tr>
                <th class="py-1 px-2 text-left">Subject</th>
                <th class="py-1 px-2 text-left">Date</th>
                <th class="py-1 px-2 text-left">Time</th>
                <th class="py-1 px-2 text-left">Status</th>
            </tr>
        </thead>

        <tbody>
            {{-- Example 1: Attended --}}
            <tr class="border-t text-xs">
                <td class="py-1 px-2">Mathematics</td>
                <td class="py-1 px-2">21/10/24</td>
                <td class="py-1 px-2">8:00am</td>
                <td class="py-1 px-2">
                    <span class="text-green-600 text-lg">✔</span>
                </td>
            </tr>

            {{-- Example 2: Missed --}}
            <tr class="border-t text-xs">
                <td class="py-1 px-2">English</td>
                <td class="py-1 px-2">22/10/24</td>
                <td class="py-1 px-2">10:00am</td>
                <td class="py-1 px-2">
                    <span class="text-red-600 text-lg">✖</span>
                </td>
            </tr>

            {{-- Example 3: Make-up lesson --}}
            <tr class="border-t text-xs">
                <td class="py-1 px-2">Biology</td>
                <td class="py-1 px-2">23/10/24</td>
                <td class="py-1 px-2">11:30am</td>
                <td class="py-1 px-2">
                    <span class="text-orange-500 text-lg">✔</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- CBC LEARNING AREAS TABLE --}}
<div class="bg-white shadow rounded p-4 overflow-x-auto">
    <h2 class="text-lg font-semibold text-gray-700 mb-3">CBC Attendance</h2>

    <table class="min-w-full border border-gray-200 table-auto whitespace-nowrap text-sm">
        <thead class="bg-school-green text-white text-xs">
            <tr>
                <th class="py-1 px-2 text-left">Learning Area</th>
                <th class="py-1 px-2 text-left">Date</th>
                <th class="py-1 px-2 text-left">Time</th>
                <th class="py-1 px-2 text-left">Status</th>
            </tr>
        </thead>

        <tbody>
            {{-- Example 1: Attended --}}
            <tr class="border-t text-xs">
                <td class="py-1 px-2">Mathematical Activities</td>
                <td class="py-1 px-2">20/10/24</td>
                <td class="py-1 px-2">9:00am</td>
                <td class="py-1 px-2">
                    <span class="text-green-600 text-lg">✔</span>
                </td>
            </tr>

            {{-- Example 2: Missed --}}
            <tr class="border-t text-xs">
                <td class="py-1 px-2">Literacy Activities</td>
                <td class="py-1 px-2">22/10/24</td>
                <td class="py-1 px-2">1:00pm</td>
                <td class="py-1 px-2">
                    <span class="text-red-600 text-lg">✖</span>
                </td>
            </tr>

            {{-- Example 3: Make-up lesson --}}
            <tr class="border-t text-xs">
                <td class="py-1 px-2">Environmental Activities</td>
                <td class="py-1 px-2">23/10/24</td>
                <td class="py-1 px-2">2:00pm</td>
                <td class="py-1 px-2">
                    <span class="text-orange-500 text-lg">✔</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
