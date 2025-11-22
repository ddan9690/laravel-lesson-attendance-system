@extends('layouts.app')

@section('title', 'Import Students to ' . $stream->name)

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Import Students to Stream: {{ $stream->name }}
</h1>

<div class="bg-white shadow rounded p-6 max-w-xl mx-auto">

    <form 
        action="{{ route('admin.students.import.grade.store', $stream->id) }}" 
        method="POST" 
        enctype="multipart/form-data"
    >
        @csrf

        {{-- Joined Academic Year / Term --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Joined Academic Year & Term</label>

            <div class="mb-2">
                <select name="joined_academic_year_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="">-- Select Academic Year --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->year }}</option>
                    @endforeach
                </select>
                @error('joined_academic_year_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <select name="joined_term_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="">-- Select Term --</option>
                    @foreach($academicYears as $year)
                        @foreach($year->terms as $term)
                            <option value="{{ $term->id }}">{{ $term->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                @error('joined_term_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Excel File --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Excel File</label>
            <input type="file" name="students_file" accept=".xlsx,.xls,.csv" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('students_file') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit"
                    class="bg-school-green text-white px-6 py-2 rounded hover:bg-green-700">
                Import Students
            </button>
            <a href="{{ route('classes.streams.students', $stream->id) }}"
               class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>

    </form>
</div>
@endsection
