@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="p-4">

    <a href="{{ route('dashboard.myAttendance') }}"
       class="bg-blue-600 text-white px-6 py-3 rounded-xl shadow hover:bg-blue-700 font-semibold text-base">
        View My Lesson Attendance
    </a>

</div>
@endsection
