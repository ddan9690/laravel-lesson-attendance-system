@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold text-school-green mb-4">Edit Teacher</h1>

    <form action="{{ route('teacher.update', [$teacher->id, $teacher->slug]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $teacher->name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                placeholder="Enter teacher's name" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                placeholder="Enter teacher's email" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-medium mb-1">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $teacher->phone) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                placeholder="Enter phone number" required>
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Code (read-only) -->
        <div class="mb-4">
            <label for="code" class="block text-gray-700 font-medium mb-1">Code</label>
            <input type="text" id="code" value="{{ $teacher->code }}" 
                class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" readonly>
            <p class="text-gray-500 text-sm mt-1">Login code is the last 4 digits of the phone.</p>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-2">
            <a href="{{ route('teachers.index') }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 rounded bg-school-green text-white hover:bg-green-700">Update</button>
        </div>
    </form>
</div>
@endsection
