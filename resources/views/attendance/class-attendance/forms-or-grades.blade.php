@extends('layouts.app')

@section('title', $curriculum->name . ' Attendance')

@section('content')
<div class="p-4 md:p-6">

    <h1 class="text-2xl font-bold text-green-800 mb-4">
        {{ $curriculum->name }} - Select {{ $curriculum->name === '8-4-4' ? 'Form' : 'Grade' }}
    </h1>

    <p class="text-gray-700 mb-6">
        Click a {{ $curriculum->name === '8-4-4' ? 'form' : 'grade' }} to view attendance summary.
    </p>

    @if($formsOrGrades->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($formsOrGrades as $item)
                <a href="{{ route('classAttendanceByFormOrGrade', ['curriculum' => $curriculum->id, 'item' => $item->id]) }}"
                   class="block bg-white shadow rounded-lg p-4 hover:bg-green-50 border border-green-200 text-center font-medium text-gray-800">
                    {{ $item->name }}
                </a>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No {{ $curriculum->name === '8-4-4' ? 'forms' : 'grades' }} found.</p>
    @endif

</div>
@endsection
