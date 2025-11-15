@extends('layouts.app')

@section('title', 'Remedial Committee Member Dashboard')

@section('content')
<div class="p-6">
    <!-- Remedial Committee Member Area -->
    <h1 class="text-3xl font-bold text-white mb-6">
        Remedial Committee Member Area
    </h1>

    <div class="bg-white text-gray-800 rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3">Welcome, {{ $user->name }}</h2>
        <p class="text-gray-600">
            You are logged in as a <span class="font-bold text-green-700">Remedial Committee Member</span>.
        </p>

        <p class="mt-4">
            This is the remedial committee member area. You can oversee and coordinate remedial lessons, 
            review student performance reports, and collaborate with teachers to improve academic progress.
        </p>
    </div>
</div>
@endsection
