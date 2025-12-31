@extends('layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="p-6">

    <!-- PRIMARY ACTION (FIRST THING ADMIN SEES) -->
    <div class="mb-8">
        @can('lesson_capture')
            <a
                href="{{ route('attendance.create') }}"
                class="w-full md:w-auto inline-block bg-yellow-700 text-white px-6 py-3 rounded-xl text-lg font-semibold hover:bg-yellow-800 shadow">
                ➕ Capture New Lesson Attendance
            </a>
        @endcan
    </div>

    <!-- FILTER BAR -->
    <div class="bg-white rounded-2xl shadow p-4 mb-8">
        <div class="flex flex-wrap gap-4 items-end">

            <div>
                <label class="text-sm font-semibold">Academic Year</label>
                <select class="border rounded p-2">
                    <option>2024</option>
                    <option>2023</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Term</label>
                <select class="border rounded p-2">
                    <option>Term 1</option>
                    <option>Term 2</option>
                    <option>Term 3</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Week</label>
                <select class="border rounded p-2">
                    <option>All Weeks</option>
                    <option>Week 1</option>
                    <option>Week 2</option>
                    <option>Week 3</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold">Quick Range</label>
                <select class="border rounded p-2">
                    <option>Last 1 Week</option>
                    <option>Last 2 Weeks</option>
                    <option>Last 3 Weeks</option>
                </select>
            </div>

            <button class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-800">
                Apply Filters
            </button>
        </div>
    </div>

    <!-- CURRICULUM SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        <!-- 8-4-4 -->
        <div class="bg-blue-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold mb-2">8-4-4 Curriculum</h2>
            <p class="text-gray-700">
                Attendance Rate: <strong>88%</strong>
            </p>
            <p class="text-gray-700">
                Lessons Missed: <strong>14%</strong>
            </p>
            <p class="text-gray-700 mt-2">
                Most Missed Form: <strong>Form 3</strong>
            </p>
            <p class="text-gray-700">
                Stream with Most Missed: <strong>Form 3 East</strong>
            </p>
        </div>

        <!-- CBC -->
        <div class="bg-green-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-bold mb-2">CBC Curriculum</h2>
            <p class="text-gray-700">
                Attendance Rate: <strong>92%</strong>
            </p>
            <p class="text-gray-700">
                Lessons Missed: <strong>8%</strong>
            </p>
            <p class="text-gray-700 mt-2">
                Most Missed Grade: <strong>Grade 6</strong>
            </p>
            <p class="text-gray-700">
                Stream with Most Missed: <strong>Grade 6 West</strong>
            </p>
        </div>

    </div>

    <!-- FORM / GRADE ANALYTICS -->
    <div class="bg-white rounded-2xl shadow p-6 mb-10">
        <h2 class="text-2xl font-bold mb-4">Form / Grade Attendance Analysis</h2>

        <table class="min-w-full table-auto text-sm">
            <thead class="bg-school-green text-white">
                <tr>
                    <th class="px-3 py-2 text-left">Curriculum</th>
                    <th class="px-3 py-2 text-left">Form / Grade</th>
                    <th class="px-3 py-2 text-left">Attendance %</th>
                    <th class="px-3 py-2 text-left">Lessons Missed</th>
                    <th class="px-3 py-2 text-left">Make-ups Done</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b even:bg-green-50">
                    <td class="px-3 py-2">8-4-4</td>
                    <td class="px-3 py-2">Form 3</td>
                    <td class="px-3 py-2">82%</td>
                    <td class="px-3 py-2">9</td>
                    <td class="px-3 py-2 text-green-700 font-semibold">6</td>
                </tr>

                <tr class="border-b even:bg-green-50">
                    <td class="px-3 py-2">CBC</td>
                    <td class="px-3 py-2">Grade 6</td>
                    <td class="px-3 py-2">85%</td>
                    <td class="px-3 py-2">5</td>
                    <td class="px-3 py-2 text-green-700 font-semibold">4</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- TEACHER ATTENDANCE ANALYTICS -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Teacher Attendance Analysis</h2>

        <table class="min-w-full table-auto text-sm">
            <thead class="bg-school-green text-white">
                <tr>
                    <th class="px-3 py-2 text-left">Teacher</th>
                    <th class="px-3 py-2 text-left">Lessons Assigned</th>
                    <th class="px-3 py-2 text-left">Lessons Missed</th>
                    <th class="px-3 py-2 text-left">Make-ups Done</th>
                    <th class="px-3 py-2 text-left">Missed %</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b even:bg-green-50">
                    <td class="px-3 py-2">Mr. Otieno</td>
                    <td class="px-3 py-2">24</td>
                    <td class="px-3 py-2 text-red-700 font-semibold">6</td>
                    <td class="px-3 py-2 text-green-700">4</td>
                    <td class="px-3 py-2">25%</td>
                </tr>

                <tr class="border-b even:bg-green-50">
                    <td class="px-3 py-2">Ms. Achieng</td>
                    <td class="px-3 py-2">18</td>
                    <td class="px-3 py-2">2</td>
                    <td class="px-3 py-2 text-green-700">2</td>
                    <td class="px-3 py-2">11%</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
