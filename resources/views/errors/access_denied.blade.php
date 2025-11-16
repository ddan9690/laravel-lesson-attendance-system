@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-5xl font-bold text-red-600 mb-4">Access Denied</h1>
        <p class="text-lg text-gray-700 mb-6">
            You do not have permission to access this page.
        </p>
        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Go to Dashboard
        </a>
    </div>
</div>
@endsection
