@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Card Example -->
    <div class="bg-card shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold text-school-green">Total Students</h3>
        <p class="text-3xl font-bold mt-2">1,204</p>
    </div>

    <div class="bg-card shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold text-school-green">Fees Collected</h3>
        <p class="text-3xl font-bold mt-2">KSh 3.2M</p>
    </div>

    <div class="bg-card shadow rounded-xl p-6">
        <h3 class="text-lg font-semibold text-school-green">Active Staff</h3>
        <p class="text-3xl font-bold mt-2">56</p>
    </div>
</div>
@endsection
