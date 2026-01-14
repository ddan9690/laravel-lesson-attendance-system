@extends('layouts.app')

@section('title', 'Class Attendance')

@section('content')
<div class="p-4 md:p-6">

    <h1 class="text-2xl font-bold text-green-800 mb-4">Class Attendance</h1>
    <p class="text-gray-700 mb-6">Select a curriculum to view attendance records.</p>

    @if($curriculums->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($curriculums as $curriculum)
                <a href="{{ route('classAttendanceByCurriculum', $curriculum->id) }}"
                   class="block bg-white shadow rounded-lg p-4 hover:bg-green-50 border border-green-200 text-center font-medium text-gray-800">
                    {{ $curriculum->name }}
                </a>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No curricula found.</p>
    @endif

</div>
@endsection
