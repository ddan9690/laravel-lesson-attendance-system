@extends('remedial.layouts.master')
@section('title', 'Attendance Detail')

@section('content')
    <div class="card">
        <h5 class="card-header">{{ $user->name }}'s Attendance for Week {{ $week->week_number }}</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Form</th>
                            <th>Subject</th>
                            <th>Lesson</th>
                            @can('admin')
                            <th>Action</th>
                          @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if ($attendances->isEmpty())
                            <tr>
                                <td style="text-align: center" colspan="5">No lesson found!</td>
                            </tr>
                        @else
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td style="white-space: nowrap">{{ date('D d/m/y', strtotime($attendance->created_at)) }}</td>
                                    <td style="white-space: nowrap">{{ $attendance->form->name }}</td>
                                    <td>{{ $attendance->subject->name }}</td>
                                    <td>
                                        @if ($attendance->lesson->name === 'L1')
                                            6:10-6:50 am
                                        @elseif ($attendance->lesson->name === 'L2')
                                            6:50-7:30am
                                        @elseif ($attendance->lesson->name === 'L3')
                                            6:30-7:10pm
                                        @elseif ($attendance->lesson->name === 'Practical')
                                            Practical
                                        @endif
                                    </td>

                                    @can('admin')
                                    <td>
                                        <form method="POST" action="{{ route('attendance.delete', ['id' => $attendance->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this attendance?')">Delete</button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
