@extends('remedial.layouts.master')

@section('title', 'Form Attendance')

@section('content')
<div class="col-md-8">
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%;">Week</th>
                        <th style="width: 10%;"> Lessons</th>
                        <th style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($weeks as $week)
                    <tr>
                        <td style="width: 5%;">Week {{ $week->week_number }}</td>
                        <td style="width: 10%;">{{ $form->attendances->where('week_id', $week->id)->count() }}</td>
                        <td style="width: 10%;"><a href="{{ route('form.attendance.week', ['id' => $form->id, 'week_id' => $week->id]) }}">View Details</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
