@extends('layouts.app')

@section('title', 'Edit ' . ucfirst($type))

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Edit {{ ucfirst($type) }} ({{ $curriculum->name }})
</h1>

<div class="bg-white shadow rounded p-6 max-w-lg">
    <form action="{{ route('subjects.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Name <span class="text-red-600">*</span></label>
            <input type="text" name="name" value="{{ old('name', $item->name) }}"
                   class="w-full border rounded p-2" required>
        </div>

        {{-- Short --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Short</label>
            <input type="text" name="short" value="{{ old('short', $item->short) }}"
                   class="w-full border rounded p-2">
        </div>

        {{-- Code --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Code</label>
            <input type="text" name="code" value="{{ old('code', $item->code) }}"
                   class="w-full border rounded p-2">
        </div>

        <button type="submit" class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">
            Save Changes
        </button>

        <a href="{{ route('subjects.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
