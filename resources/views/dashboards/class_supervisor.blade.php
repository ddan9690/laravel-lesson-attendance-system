@extends('layouts.app')

@section('title', 'Class Supervisor Dashboard')

@section('content')
<div class="p-6">
    <!-- Class Supervisor Area -->
    <h1 class="text-3xl font-bold text-white mb-6">
        Class Supervisor Area
    </h1>

    <div class="bg-white text-gray-800 rounded-2xl shadow p-6">
        {{-- <h2 class="text-xl font-semibold mb-3">Welcome, {{ $user->name }}</h2> --}}
        <p class="text-gray-600">
            You are logged in as a <span class="font-bold text-green-700">Class Supervisor</span>.
        </p>

        <p class="mt-4">
            This is the class supervisor area. You can manage class attendance, monitor teachers’ lesson progress, 
            and view reports for your assigned forms or grades.
        </p>
    </div>
</div>
@endsection
