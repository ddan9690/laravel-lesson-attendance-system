@extends('remedial.layouts.master')

@section('title', 'Week Attendance')

@section('content')
<div class="col-md-8">
    <div class="card">
        {{-- <h4 class="mt-3 text-center">Total Lessons: {{ $attendances->count() }}</h4> --}}
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 3%;">#</th>
                        <th>Date</th>
                        <th>Subject</th>
                        <th style="width: 20%;">Lesson</th>
                        <th>Teacher</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $rowNumber = 1;
                    @endphp
                    @foreach ($attendances as $lessonAttendances)
                        @foreach ($lessonAttendances as $attendance)
                            <tr>
                                <td>{{ $rowNumber++ }}</td>
                                <td>{{ $attendance->created_at->format('D d/m/y') }}</td>
                                <td>{{ $attendance->subject->name }}</td>
                                <td>{{ $attendance->lesson->name }}</td>
                                <td>{{ $attendance->user->name }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
