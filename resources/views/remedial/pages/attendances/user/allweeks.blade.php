@extends('remedial.layouts.master')

@section('title', 'Attendance')

@section('content')
<style>
    .table-sm td,
    .table-sm th {
        white-space: nowrap;
        padding: 0.5rem;
        text-align: center; /* Add this line to center align text */
    }

</style>
<div class="col-md-6 mx-auto">
    <div class="card">
        <h5 class="card-header">{{ auth()->user()->name }}: <span><strong>Total-{{ auth()->user()->attendances->count() }}</strong></span></h5>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th>No. of Lessons</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weeks as $week)
                            <tr>
                                <td>Week {{ $week->week_number }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ auth()->user()->attendances->where('week_id', $week->id)->count() }}</td>
                                <td>
                                    <a href="{{ route('user.viewweekly', ['week' => $week->week_number, 'user_id' => auth()->user()->id]) }}" class="btn btn-sm btn-info">Click to View</a>
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
