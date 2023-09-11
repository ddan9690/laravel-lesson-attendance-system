@extends('remedial.layouts.master')
@section('title', 'Attendance Detail')

@section('content')
    <div class="col-md-8 col-sm-12">
        <div class="card">
            <h5 class="card-header"> <strong>Week {{ $week->week_number }}</strong></h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Form</th>
                                <th>Subject</th>
                                <th>Lesson</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($attendances->isEmpty())
                                <tr>
                                    <td style="text-align: center" colspan="4">No lesson found!</td>
                                </tr>
                            @else
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td style="white-space: nowrap">{{ date('D d/m/y', strtotime($attendance->created_at)) }}</td>
                                        <td style="white-space: nowrap">{{ $attendance->form->name }}</td>
                                        <td>{{ $attendance->subject->name }}</td>
                                        <td>
                                            @if (in_array($attendance->lesson->name, ['L1', 'L2', 'L3', 'Morning', 'Evening']))
                                                @if ($attendance->lesson->name === 'L1')
                                                    6:10-6:50 am
                                                @elseif ($attendance->lesson->name === 'L2')
                                                    6:50-7:30 am
                                                @elseif ($attendance->lesson->name === 'L3')
                                                    6:30-7:10 pm
                                                @elseif ($attendance->lesson->name === 'Morning')
                                                    6:10-7:10 am
                                                @elseif ($attendance->lesson->name === 'Evening')
                                                    6:30-7:30 pm
                                                @endif
                                            @elseif ($attendance->lesson->name === 'Practical')
                                                Practical
                                            @else
                                                <!-- Handle other lesson names here if needed -->
                                                {{ $attendance->lesson->name }}
                                            @endif
                                        </td>
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
