@extends('layouts.app')

@section('title', 'Edit Stream')

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Edit Stream ({{ ucfirst($type) }}: {{ $item->name }})
</h1>

<div class="bg-white shadow rounded p-6 max-w-lg">
    <form action="{{ route('classes.streams.update', $stream->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Parent Class (disabled) --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">{{ $type === 'form' ? 'Form' : 'Grade' }}</label>
            <input type="text" class="w-full border rounded p-2 bg-gray-100" disabled
                   value="{{ $item->name }}">
        </div>

        {{-- Stream Name --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Stream Name <span class="text-red-600">*</span></label>
            <input type="text" name="name" class="w-full border rounded p-2"
                   value="{{ old('name', $stream->name) }}" required>
        </div>

        {{-- Class Teacher --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Class Teacher (Optional)</label>
            <select name="class_teacher_id" class="w-full border rounded p-2">
                <option value="">-- None --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"
                        {{ $stream->class_teacher_id == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-6">
            <button class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">Update Stream</button>
            <a href="{{ route('classes.streams.showStreams', ['type' => $type, 'id' => $item->id]) }}"
               class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
