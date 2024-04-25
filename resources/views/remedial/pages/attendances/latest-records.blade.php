@extends('remedial.layouts.master')

@section('title', 'Latest Attendance Records')

@section('content')
<style>
    .table-sm td,
    .table-sm th {
        white-space: nowrap;
        padding: 0.5rem;

    }

</style>
<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Latest Attendance Records</h5>
        </div>
        <div class="card-body">
            <div class="">
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-primary">Back</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Teacher</th>
                            <th>Lesson</th>
                            <th>Date</th>
                            {{-- <th>Added by</th> <!-- Added a new column for "Added by" --> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestAttendances as $attendance)
                        <tr>
                            <td>{{ $attendance->form->name }}</td>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->lesson->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->created_at)->format('d/m/y') }}</td>
                            <!-- Retrieve the first name of the user who added the attendance record -->
                            {{-- <td>{{ explode(' ', auth()->user()->name)[0] }}</td> --}}

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No remedial lesson found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
