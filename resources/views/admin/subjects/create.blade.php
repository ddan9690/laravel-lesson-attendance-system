@extends('layouts.app')

@section('title', 'Add ' . ucfirst($type))

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Add New {{ ucfirst($type) }} ({{ $curriculum->name }})
</h1>

<div class="bg-white shadow rounded p-6 max-w-lg">
    <form action="{{ route('subjects.store') }}" method="POST">
        @csrf

        {{-- Curriculum (Hidden) --}}
        <input type="hidden" name="curriculum_id" value="{{ $curriculum->id }}">

        {{-- Type (Hidden) --}}
        <input type="hidden" name="type" value="{{ $type }}"> {{-- 'subject' or 'learning_area' --}}

        {{-- Name --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Name <span class="text-red-600">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded p-2"
                   placeholder="Enter Name" required>
        </div>

        {{-- Short --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Short</label>
            <input type="text" name="short" value="{{ old('short') }}"
                   class="w-full border rounded p-2"
                   placeholder="Enter Short Name">
        </div>

        {{-- Code --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Code</label>
            <input type="text" name="code" value="{{ old('code') }}"
                   class="w-full border rounded p-2"
                   placeholder="Enter Code">
        </div>

        {{-- Submit --}}
        <div class="mt-6">
            <button type="submit" class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">
                Add {{ ucfirst($type) }}
            </button>

            <a href="{{ route('subjects.index') }}" class="ml-2 text-gray-600 hover:underline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
