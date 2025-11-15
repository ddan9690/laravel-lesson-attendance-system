@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        Change Password
    </h1>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.password.update') }}" method="POST" class="bg-white shadow rounded-xl p-6">
        @csrf

        <div class="mb-4">
            <label for="new_password" class="block text-gray-700 font-semibold mb-2">New Password</label>
            <input type="password" name="new_password" id="new_password" 
                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500" 
                required>
        </div>

        <div class="mb-4">
            <label for="new_password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                class="w-full border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500" 
                required>
        </div>

        <!-- Buttons side by side -->
        <div class="flex space-x-3">
            <button type="submit" 
                class="bg-green-700 text-white px-4 py-1.5 rounded-lg hover:bg-green-800 font-semibold">
                Update
            </button>

            <a href="{{ route('dashboard.teacher') }}" 
               class="bg-gray-700 text-white px-4 py-1.5 rounded-lg hover:bg-gray-800 font-semibold">
                Close
            </a>
        </div>
    </form>
</div>
@endsection
