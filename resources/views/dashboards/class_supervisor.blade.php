@extends('layouts.app')

@section('title', 'Class Supervisor Dashboard')

@section('content')
<div class="p-6">

    <!-- Class Supervisor Header -->
    <div class="bg-white text-gray-800 rounded-2xl shadow p-6 mb-6">
        <h2 class="text-xl font-semibold">
            Logged in as: {{ auth()->user()->name }} – 
            <span class="font-bold text-green-700">Class Supervisor</span>
        </h2>
        <p class="mt-2 text-gray-600">
            You are managing <span class="font-bold text-green-700">Grade 10</span>.
        </p>
    </div>

    <!-- Payment & Lesson Cards for Grade 10 -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Total Collected -->
        <div class="bg-blue-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Collected</h2>
            <p class="text-gray-700 text-2xl font-bold">{{ number_format(350000) }}</p>
            <p class="text-gray-500 text-sm mt-1">From 150 students out of 200</p>
        </div>

        <!-- Clearance Collection -->
        <div class="bg-green-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Collection from Clearances</h2>
            <p class="text-gray-700 text-2xl font-bold">{{ number_format(50000) }}</p>
            <p class="text-gray-500 text-sm mt-1">Clearance payments for Grade 10</p>
        </div>

        <!-- Lessons Info -->
        <div class="bg-yellow-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Lessons Missed</h2>
            <p class="text-gray-700 text-2xl font-bold">3</p>
            <p class="text-gray-500 text-sm mt-1">Lessons missed this week</p>
        </div>
    </div>

    <!-- Grade 10 Payment Table -->
    @php
        $streams = ['Diamond', 'Sapphire', 'Topaz', 'Emerald', 'Gold', 'Pearl'];
    @endphp

    <div class="bg-white shadow rounded p-4 overflow-x-auto">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Grade 10 Payments Overview</h2>
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
                @foreach($streams as $stream)
                    <tr class="border-b border-gray-200 even:bg-green-50 hover:bg-school-accent hover:text-white transition-colors">
                        <td class="py-2 px-3">{{ $stream }}</td>
                        <td class="py-2 px-3">{{ number_format(rand(30000,80000)) }}</td>
                        <td class="py-2 px-3">{{ rand(20,50) }} / 50</td>
                        <td class="py-2 px-3">John Doe</td>
                        <td class="py-2 px-3">
                            <button class="bg-school-green text-white px-3 py-1 rounded hover:bg-green-800 text-xs">View Details</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
