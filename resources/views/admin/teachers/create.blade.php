@extends('layouts.app')

@section('title', 'Add New Teacher')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold text-school-green mb-6">Add New Teacher</h1>

    <form action="{{ route('teacher.store') }}" method="POST">
        @csrf

        {{-- ================= BASIC INFO ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-medium mb-1">Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block font-medium mb-1">Phone</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        {{-- ================= ACTIONS ================= --}}
        <div class="flex justify-end space-x-3">
            <a href="{{ route('teachers.index') }}"
               class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2 bg-school-green text-white rounded hover:bg-green-700">
                Save Teacher
            </button>
        </div>
    </form>
</div>
@endsection
