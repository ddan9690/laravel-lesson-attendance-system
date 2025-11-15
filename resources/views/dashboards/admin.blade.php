@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Admin Area Header -->
    <h1 class="text-3xl font-bold text-white mb-6">
        Super Admin Dashboard
    </h1>

    <!-- Welcome Section -->
    <div class="bg-white text-gray-800 rounded-2xl shadow p-6 mb-6">
        <p class="text-gray-600">
            Welcome, <span class="font-bold text-green-700">Administrator</span>!
        </p>
        <p class="mt-2">
            You have full authority to manage teachers, classes, remedial committees, subjects, and system settings.
        </p>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Teachers Card -->
        <div class="bg-green-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">Teachers</h2>
            <p class="text-gray-700 mb-4">Manage all school teachers. Add, edit, or remove teacher accounts.</p>
            <ul class="list-disc list-inside text-gray-600 mb-4">
                <li>John Doe</li>
                <li>Mary Wanjiru</li>
                <li>David Obiero</li>
            </ul>
            <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Manage Teachers</button>
        </div>

        <!-- Classes Card -->
        <div class="bg-blue-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">Classes</h2>
            <p class="text-gray-700 mb-4">Manage school classes, assign teachers, and track students.</p>
            <ul class="list-disc list-inside text-gray-600 mb-4">
                <li>Form 1A</li>
                <li>Form 2B</li>
                <li>Form 3C</li>
            </ul>
            <button class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Manage Classes</button>
        </div>

        <!-- Subjects Card -->
        <div class="bg-yellow-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">Subjects</h2>
            <p class="text-gray-700 mb-4">Manage subjects, assign teachers, and create subject materials.</p>
            <ul class="list-disc list-inside text-gray-600 mb-4">
                <li>Mathematics</li>
                <li>English</li>
                <li>Biology</li>
            </ul>
            <button class="bg-yellow-700 text-white px-4 py-2 rounded hover:bg-yellow-800">Manage Subjects</button>
        </div>

        <!-- Remedial Committee Card -->
        <div class="bg-purple-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">Remedial Committee</h2>
            <p class="text-gray-700 mb-4">Manage remedial programs and committee members.</p>
            <ul class="list-disc list-inside text-gray-600 mb-4">
                <li>Dominic Obiri</li>
                <li>Ruth Oketch</li>
                <li>Philip Odhiambo</li>
            </ul>
            <button class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800">Manage Remedial</button>
        </div>

        <!-- Reports Card -->
        <div class="bg-red-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">Reports</h2>
            <p class="text-gray-700 mb-4">View school reports and performance analytics.</p>
            <button class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">View Reports</button>
        </div>

        <!-- System Settings Card -->
        <div class="bg-gray-100 rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">System Settings</h2>
            <p class="text-gray-700 mb-4">Configure system preferences and access controls.</p>
            <button class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Manage Settings</button>
        </div>
    </div>
</div>
@endsection
