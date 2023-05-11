@extends('remedial.layouts.master')
@section('title', 'Attendance Detail')

@section('content')
    <div class="col-md-8 col-sm-12">
        <div class="card">
            <h5 class="card-header"> <strong>Week {{ $week->week_number }}</strong></h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Form</th>
                                <th>Subject</th>
                                <th>Lesson</th>
                                <th>Status</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @if ($attendances->isEmpty())
                                <tr>
                                    <td  style="text-align: center" colspan="6">No lesson found!</td>
                                </tr>
                            @else
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td style="white-space: nowrap">{{ date('D d-m-Y', strtotime($attendance->created_at)) }}</td>
                                        <td style="white-space: nowrap">{{ $attendance->form->name }}</td>
                                        <td>{{ $attendance->subject->name }}</td>
                                        <td>{{ $attendance->lesson->name }}</td>
                                        <td style="white-space: nowrap">
                                            @if ($attendance->status === null)
                                                <i class="bx bxs-check-circle menu-icon tf-icons bx-sm text-success"></i>
                                            @else
                                                {{ $attendance->status }}
                                            @endif
                                        </td>
                                        {{-- <td>
                                            <form method="POST" action="{{ route('attendance.delete', ['id' => $attendance->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this attendance?')">Delete</button>
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
