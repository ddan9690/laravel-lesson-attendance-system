@extends('layouts.app')

@section('title', 'Moi Nyabohanse Girls High School Remedial System')

@section('content')
<div class="p-6 flex flex-col items-center justify-center min-h-screen bg-green-50">
    <!-- User Info Card -->
    <div class="bg-white text-gray-800 rounded-2xl shadow-lg p-6 w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-4">User Information</h2>
        
        <p class="text-lg"><span class="font-semibold">Name:</span> {{ $user->name }}</p>
        <p class="text-lg"><span class="font-semibold">Email:</span> {{ $user->email }}</p>
        <p class="text-lg"><span class="font-semibold">Role(s):</span> {{ $user->getRoleNames()->join(', ') }}</p>
    </div>
</div>
@endsection
