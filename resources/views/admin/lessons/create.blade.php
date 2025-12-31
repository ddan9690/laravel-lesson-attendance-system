@extends('layouts.app')

@section('title', 'Add Lesson')

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Add New Lesson
</h1>

<div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6"
     x-data="{ submitting: false }">

    <form 
        action="{{ route('lessons.store') }}" 
        method="POST"
        @submit.prevent="submitting = true; $el.submit()"
    >
        @csrf

        {{-- Curriculum --}}
        <div class="mb-4">
            <label for="curriculum_id" class="block text-gray-700 font-semibold mb-1">Curriculum</label>
            <select name="curriculum_id" id="curriculum_id" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                    required>
                <option value="">Select Curriculum</option>
                @foreach($curricula as $curriculum)
                    <option value="{{ $curriculum->id }}">{{ $curriculum->name }}</option>
                @endforeach
            </select>
            @error('curriculum_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lesson Name --}}
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-1">Lesson Name</label>
            <input type="text" name="name" id="name" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                   placeholder="e.g., Lesson 1" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Start Time --}}
        <div class="mb-4">
            <label for="start_time" class="block text-gray-700 font-semibold mb-1">Start Time</label>
            <input type="time" name="start_time" id="start_time" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                   required>
            @error('start_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- End Time --}}
        <div class="mb-4">
            <label for="end_time" class="block text-gray-700 font-semibold mb-1">End Time</label>
            <input type="time" name="end_time" id="end_time" 
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-school-green"
                   required>
            @error('end_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit" 
                    x-bind:disabled="submitting"
                    class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-50">
                <span x-show="!submitting">Save Lesson</span>
                <span x-show="submitting">Please wait...</span>
            </button>
        </div>
    </form>
</div>
@endsection
