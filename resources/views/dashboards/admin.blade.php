@extends('layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="p-6 flex flex-wrap gap-4">

    @can('lesson_capture')
        <a
            href="{{ route('attendance.create') }}"
            class="bg-yellow-700 text-white px-6 py-3 rounded-xl text-lg font-semibold hover:bg-yellow-800 shadow">
            ➕ Capture New Lesson Attendance
        </a>
    @endcan

    <a
        href="{{ route('dashboard.myAttendance') }}"
        class="bg-blue-600 text-white px-6 py-3 rounded-xl text-lg font-semibold hover:bg-blue-700 shadow">
        📄 View My Lessons
    </a>

</div>
@endsection
