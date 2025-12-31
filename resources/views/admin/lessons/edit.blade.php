@extends('layouts.app')

@section('title', 'Edit Lesson')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
    <h1 class="text-2xl font-bold text-school-green mb-6">Edit Lesson</h1>

    <form action="{{ route('lessons.update', $lesson->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Lesson Name --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Lesson Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $lesson->name) }}"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-school-green focus:border-school-green">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Curriculum Selection --}}
        <div class="mb-4">
            <label for="curriculum" class="block text-sm font-medium text-gray-700">Curriculum</label>
            <select name="curriculum" id="curriculum"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-school-green focus:border-school-green">
                @foreach($curriculaList as $curriculum)
                    <option value="{{ $curriculum->name }}"
                        {{ old('curriculum', $lesson->curriculum->name) === $curriculum->name ? 'selected' : '' }}>
                        {{ $curriculum->name }}
                    </option>
                @endforeach
            </select>
            @error('curriculum')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Start Time --}}
        <div class="mb-4">
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $lesson->start_time) }}"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-school-green focus:border-school-green">
            @error('start_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- End Time --}}
        <div class="mb-4">
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $lesson->end_time) }}"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-school-green focus:border-school-green">
            @error('end_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <a href="{{ route('lessons.index') }}" class="mr-3 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-school-green text-white rounded hover:bg-green-700">Update Lesson</button>
        </div>
    </form>
</div>
@endsection
