@extends('layouts.app')

@section('title', 'Class Teacher Dashboard')

@section('content')
<div class="p-6">

    <!-- Class Teacher Header -->
    <div class="bg-white text-gray-800 rounded-2xl shadow p-6 mb-6">
        <h2 class="text-xl font-semibold">
            Logged in as: {{ $user->name }} – 
            <span class="font-bold text-green-700">Class Teacher</span>
        </h2>
        <p class="mt-2 text-gray-600">
            You are managing <span class="font-bold text-green-700">Grade 10 – Diamond Stream</span>.
        </p>
    </div>

    <!-- Cards for the Stream -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Total Collected -->
        <div class="bg-blue-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Collected</h2>
            <p class="text-gray-700 text-2xl font-bold">{{ number_format(60000) }}</p>
            <p class="text-gray-500 text-sm mt-1">From 40 students out of 50</p>
        </div>

        <!-- Lessons Attended -->
        <div class="bg-purple-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Lessons Attended</h2>
            <p class="text-gray-700 text-2xl font-bold">45 / 50</p>
            <p class="text-gray-500 text-sm mt-1">Students who attended lessons this week</p>
        </div>

        <!-- Lessons Missed -->
        <div class="bg-red-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Lessons Missed</h2>
            <p class="text-gray-700 text-2xl font-bold">5</p>
            <p class="text-gray-500 text-sm mt-1">Students who missed lessons this week</p>
        </div>
    </div>

    <!-- Table for Students in Stream -->
    @php
        $students = [
            ['id' => 7297, 'name' => 'Awino Ida Ologi'],
            ['id' => 7303, 'name' => 'Otieno Letty Lovian'],
            ['id' => 7315, 'name' => 'Makori Moraa Sarah'],
            ['id' => 7321, 'name' => 'Stacy Wanjiru Mwangi'],
            ['id' => 7327, 'name' => 'Oketch Irine Atieno'],
            ['id' => 7333, 'name' => 'Shanice Kiribo'],
            ['id' => 7346, 'name' => 'Richard Anny Moraa'],
        ];
    @endphp

    <div class="bg-white shadow rounded p-4 overflow-x-auto">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Grade 10 – Diamond Stream Students</h2>
        <table class="min-w-full table-auto text-sm">
            <thead class="bg-school-green text-white text-xs uppercase">
                <tr>
                    <th class="py-2 px-3 text-left">Student ID</th>
                    <th class="py-2 px-3 text-left">Student Name</th>
                    <th class="py-2 px-3 text-left">Amount Paid</th>
                    <th class="py-2 px-3 text-left">Attendance</th>
                    <th class="py-2 px-3 text-left">Remarks</th>
                    <th class="py-2 px-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr class="border-b border-gray-200 even:bg-green-50 hover:bg-school-accent hover:text-white transition-colors">
                        <td class="py-2 px-3">{{ $student['id'] }}</td>
                        <td class="py-2 px-3">{{ $student['name'] }}</td>
                        <td class="py-2 px-3">{{ number_format(rand(500,2000)) }}</td>
                        <td class="py-2 px-3">{{ rand(3,5) }}/5</td>
                        <td class="py-2 px-3">Good</td>
                        <td class="py-2 px-3">
                            <button class="bg-school-green text-white px-3 py-1 rounded hover:bg-green-800 text-xs">View</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
