@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="p-6">
    <!-- Teacher Area -->
    <h1 class="text-3xl font-bold text-white mb-6">
        Teacher Area
    </h1>

    <div class="bg-white text-gray-800 rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">Welcome, {{ $user->name }}</h2>
        <p class="text-gray-600">
            You are logged in as a <span class="font-bold text-green-700">Teacher</span>.
        </p>

        <p class="mt-4">
            This is the teacher area. You can manage your lesson attendance, record lesson progress, 
            and view class or subject performance reports.
        </p>
    </div>
</div>
@endsection
