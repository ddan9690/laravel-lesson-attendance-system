@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="p-6 space-y-6">

    {{-- Welcome Card --}}
    <div class="bg-white text-gray-800 rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">
            Welcome, {{ auth()->user()->name }}
        </h2>
        <p class="text-gray-600">
            You are logged in as a 
            <span class="font-bold text-green-700">
                {{ auth()->user()->getRoleNames()->first() }}
            </span>.
        </p>

        <p class="mt-4">
            This is the teacher area. Below is a demo showing your lesson attendance for this week.
        </p>
    </div>

    {{-- Weekly Missed Lessons Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-red-100 border-l-4 border-red-500 p-6 rounded-xl shadow">
            <h3 class="text-lg font-bold text-red-700">Lessons Missed This Week</h3>
            <p class="text-4xl font-extrabold mt-2 text-red-600">3</p>
        </div>
    </div>

    {{-- 844 Curriculum Attendance --}}
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-2xl font-bold text-green-800 mb-4">844 Curriculum – Lesson Attendance</h3>

        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-200 text-gray-800">
                <tr>
                    <th class="p-3 text-left">Subject</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Time</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>

                {{-- Attended normally --}}
                <tr class="border-b">
                    <td class="p-3">Mathematics</td>
                    <td class="p-3">10/11/25</td>
                    <td class="p-3">8:00am - 9:00am</td>
                    <td class="p-3">
                        <span class="text-green-600 text-2xl">✔</span>
                    </td>
                </tr>

                {{-- Missed --}}
                <tr class="border-b">
                    <td class="p-3">English</td>
                    <td class="p-3">11/11/25</td>
                    <td class="p-3">10:00am - 11:00am</td>
                    <td class="p-3">
                        <span class="text-red-600 text-2xl">✘</span>
                    </td>
                </tr>

                {{-- Make-up (originally missed but teacher made up lesson) --}}
                <tr class="border-b">
                    <td class="p-3">Kiswahili</td>
                    <td class="p-3">11/11/25</td>
                    <td class="p-3">2:00pm - 3:00pm</td>
                    <td class="p-3">
                        <span class="text-green-600 text-2xl">✔</span>
                        <span class="text-red-600 text-xs">(make up)</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- CBC Curriculum Attendance --}}
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-2xl font-bold text-green-800 mb-4">CBC Curriculum – Learning Areas Attendance</h3>

        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-200 text-gray-800">
                <tr>
                    <th class="p-3 text-left">Learning Area</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Time</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>

                {{-- Missed --}}
                <tr class="border-b">
                    <td class="p-3">Environmental Activities</td>
                    <td class="p-3">12/11/25</td>
                    <td class="p-3">9:00am - 10:00am</td>
                    <td class="p-3">
                        <span class="text-red-600 text-2xl">✘</span>
                    </td>
                </tr>

                {{-- Attended normally --}}
                <tr class="border-b">
                    <td class="p-3">Literacy</td>
                    <td class="p-3">13/11/25</td>
                    <td class="p-3">11:00am - 12:00pm</td>
                    <td class="p-3">
                        <span class="text-green-600 text-2xl">✔</span>
                    </td>
                </tr>

                {{-- Make-up --}}
                <tr class="border-b">
                    <td class="p-3">Creative Activities</td>
                    <td class="p-3">13/11/25</td>
                    <td class="p-3">1:00pm - 2:00pm</td>
                    <td class="p-3">
                        <span class="text-green-600 text-2xl">✔</span>
                        <span class="text-red-600 text-xs">(make up)</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>
@endsection
