@extends('remedial.layouts.master')
@section('title', 'Attendance')
@section('content')
<div class="col-md-8">
    <div class="card">
        <h5 class="card-header">{{ $user->name }}: <span><strong>Total-{{$user->attendances->count()}}</strong></span></h5>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>No.of Lessons</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weeks as $week)
                            <tr>
                                <td>Week {{ $week->week_number }}</td>
                                <td>{{ $user->attendances->where('week_id', $week->id)->count() }}</td>
                                <td>
                                    <a href="{{ route('user.attendances.viewweekly', ['week' => $week->week_number, 'user_id' => $user->id]) }}" >View</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
