@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <!-- Current Academic Year -->
    <div class="bg-card shadow rounded-lg p-4">
        <h3 class="text-md font-semibold text-school-green">Current Academic Year</h3>
        <p class="text-xl font-bold mt-1">{{ $currentYear ? $currentYear->year : 'Not Set' }}</p>
    </div>

    <!-- Current Term -->
    <div class="bg-card shadow rounded-lg p-4">
        <h3 class="text-md font-semibold text-school-green">Current Term</h3>
        <p class="text-xl font-bold mt-1">{{ $currentTerm ? $currentTerm->name : 'Not Set' }}</p>
    </div>

    <!-- Current Week -->
    <div class="bg-card shadow rounded-lg p-4">
        <h3 class="text-md font-semibold text-school-green">Current Week</h3>
        <p class="text-xl font-bold mt-1">{{ $currentWeek ? $currentWeek->name : 'Not Set' }}</p>
    </div>
</div>
@endsection
