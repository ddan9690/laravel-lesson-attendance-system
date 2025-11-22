@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="p-6">

        <!-- User Info Header -->
        {{-- <div class="bg-white text-gray-800 rounded-2xl shadow p-6 mb-6">
            <h2 class="text-xl font-semibold">
                Logged in as: {{ auth()->user()->name }} –
                <span class="font-bold text-green-700">{{ auth()->user()->getRoleNames()->first() }}</span>
            </h2>
        </div> --}}

        <!-- Payment Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Total Collected This Term -->
            <div class="bg-blue-100 rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-2">Total Collected This Term</h2>
                <p class="text-gray-700 text-2xl font-bold">{{ number_format($activeTermTotal) }}</p>
                {{-- <p class="text-gray-500 text-sm mt-1">
                    From {{ array_sum(array_column($gradesAnalysis, 'students_paid')) }} students
                </p> --}}

                <button class="mt-4 bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Go to Payment
                    Management</button>
            </div>

            <!-- Clearance Collections -->
            <div class="bg-green-100 rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-2">Total Collection from Clearances</h2>
                <p class="text-gray-700 text-2xl font-bold">{{ number_format($graduatedClearanceTotal) }}</p>
                {{-- <p class="text-gray-500 text-sm mt-1">Clearance payments from graduated students</p> --}}
            </div>

            <!-- Lessons Management -->
            <div class="bg-yellow-100 rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-2">Manage Lessons</h2>
                <p class="text-gray-700 mb-4">Remedial lesson tracking and attendance management.</p>
                <button class="bg-yellow-700 text-white px-4 py-2 rounded hover:bg-yellow-800">Go to Lesson
                    Management</button>
            </div>
        </div>

        <!-- Lessons Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- 8-4-4 Attendance % -->
            <div class="bg-purple-100 rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-2">8-4-4 Attendance</h2>
                <p class="text-gray-700 text-2xl font-bold">87%</p>
            </div>

            <!-- CBC Attendance % -->
            <div class="bg-pink-100 rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-2">CBC Attendance</h2>
                <p class="text-gray-700 text-2xl font-bold">91%</p>
            </div>

            <!-- Lessons Missed This Week -->
            <div class="bg-red-100 rounded-2xl shadow p-6">
                <h2 class="text-xl font-semibold mb-2">Lessons Missed This Week</h2>
                <p class="text-gray-700 text-2xl font-bold">12</p>
            </div>
        </div>

        <!-- Payments Tables by Grade -->
        @foreach ($gradesAnalysis as $gradeData)
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ $gradeData['grade']->name }} Payments Overview</h2>

                <!-- Grade Summary Card -->
                <div class="bg-white shadow rounded p-4 mb-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">Total Collected</h3>
                        <p class="text-gray-700 font-bold text-xl">{{ number_format($gradeData['total_collected']) }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded">
                        <h3 class="text-lg font-semibold mb-2">Total Students Paid</h3>
                        <p class="text-gray-700 font-bold text-xl">{{ $gradeData['students_paid'] }} /
                            {{ $gradeData['total_students'] }}</p>
                    </div>
                </div>

                <!-- Grade Payment Table -->
                <div class="bg-white shadow rounded p-4 overflow-x-auto">
                    <table class="min-w-full table-auto text-sm">
                        <thead class="bg-school-green text-white text-xs uppercase">
                            <tr>
                                <th class="py-2 px-3 text-left">Stream</th>
                                <th class="py-2 px-3 text-left">Amount Collected</th>
                                <th class="py-2 px-3 text-left">Students Paid</th>
                                <th class="py-2 px-3 text-left">Class Teacher</th>
                                <th class="py-2 px-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gradeData['streams'] as $stream)
                                <tr
                                    class="border-b border-gray-200 even:bg-green-50 hover:bg-school-accent hover:text-white transition-colors">
                                    <td class="py-2 px-3">{{ $stream['stream_name'] }}</td>
                                    <td class="py-2 px-3">{{ number_format($stream['total_collected']) }}</td>
                                    <td class="py-2 px-3">{{ $stream['students_paid'] }} /
                                        {{ $gradeData['grade']->students->where('stream_id', $stream['stream_name'])->count() }}
                                    </td>
                                    <td class="py-2 px-3">{{ $stream['teacher'] ?? 'N/A' }}</td>
                                    <td class="py-2 px-3">
                                        <button
                                            class="bg-school-green text-white px-3 py-1 rounded hover:bg-green-800 text-xs">View
                                            Details</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>
@endsection
