@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')

    <h1 class="text-2xl font-bold text-school-green mb-6">
        Teacher Dashboard
    </h1>

    {{-- Weekly Missed Lessons Summary --}}
    <div class="bg-white shadow rounded p-4 mb-6">
        <h2 class="text-lg font-semibold text-school-green mb-2">Weekly Summary</h2>

        <p class="text-gray-700 text-sm">
            <span class="font-bold text-red-600 text-xl">3</span> lessons missed this week.
        </p>
    </div>

    {{-- 844 Curriculum --}}
    <div class="bg-white shadow rounded p-4 mb-8 overflow-x-auto">
        <h2 class="text-lg font-bold text-school-green mb-4">
            844 Curriculum – Lesson Attendance
        </h2>

        <table class="datatable min-w-full border border-gray-200 table-auto whitespace-nowrap text-sm">
            <thead class="bg-school-green text-white text-xs">
                <tr>
                    <th class="py-1 px-2 text-left">Subject</th>
                    <th class="py-1 px-2 text-left">Date</th>
                    <th class="py-1 px-2 text-left">Time</th>
                    <th class="py-1 px-2 text-left">Status</th>
                </tr>
            </thead>

            <tbody>

                {{-- Attended --}}
                <tr class="border-t text-xs">
                    <td class="py-1 px-2">Mathematics</td>
                    <td class="py-1 px-2">10/11/25</td>
                    <td class="py-1 px-2">8:00am - 9:00am</td>
                    <td class="py-1 px-2">
                        <span class="text-green-600 text-xl">✔</span>
                    </td>
                </tr>

                {{-- Missed --}}
                <tr class="border-t text-xs">
                    <td class="py-1 px-2">English</td>
                    <td class="py-1 px-2">11/11/25</td>
                    <td class="py-1 px-2">10:00am - 11:00am</td>
                    <td class="py-1 px-2">
                        <span class="text-red-600 text-xl">✘</span>
                    </td>
                </tr>

                {{-- Make-up --}}
                <tr class="border-t text-xs">
                    <td class="py-1 px-2">Kiswahili</td>
                    <td class="py-1 px-2">11/11/25</td>
                    <td class="py-1 px-2">2:00pm - 3:00pm</td>
                    <td class="py-1 px-2">
                        <span class="text-green-600 text-xl">✔</span>
                        <span class="text-red-600 text-[10px]">(make up)</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- CBC Curriculum --}}
    <div class="bg-white shadow rounded p-4 overflow-x-auto">
        <h2 class="text-lg font-bold text-school-green mb-4">
            CBC Curriculum – Learning Areas Attendance
        </h2>

        <table class="datatable min-w-full border border-gray-200 table-auto whitespace-nowrap text-sm">
            <thead class="bg-school-green text-white text-xs">
                <tr>
                    <th class="py-1 px-2 text-left">Learning Area</th>
                    <th class="py-1 px-2 text-left">Date</th>
                    <th class="py-1 px-2 text-left">Time</th>
                    <th class="py-1 px-2 text-left">Status</th>
                </tr>
            </thead>

            <tbody>

                {{-- Missed --}}
                <tr class="border-t text-xs">
                    <td class="py-1 px-2">Environmental Activities</td>
                    <td class="py-1 px-2">12/11/25</td>
                    <td class="py-1 px-2">9:00am - 10:00am</td>
                    <td class="py-1 px-2">
                        <span class="text-red-600 text-xl">✘</span>
                    </td>
                </tr>

                {{-- Attended --}}
                <tr class="border-t text-xs">
                    <td class="py-1 px-2">Literacy</td>
                    <td class="py-1 px-2">13/11/25</td>
                    <td class="py-1 px-2">11:00am - 12:00pm</td>
                    <td class="py-1 px-2">
                        <span class="text-green-600 text-xl">✔</span>
                    </td>
                </tr>

                {{-- Make-up --}}
                <tr class="border-t text-xs">
                    <td class="py-1 px-2">Creative Activities</td>
                    <td class="py-1 px-2">13/11/25</td>
                    <td class="py-1 px-2">1:00pm - 2:00pm</td>
                    <td class="py-1 px-2">
                        <span class="text-green-600 text-xl">✔</span>
                        <span class="text-red-600 text-[10px]">(make up)</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

@endsection
