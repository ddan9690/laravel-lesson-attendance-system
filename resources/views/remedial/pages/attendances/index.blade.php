@extends('remedial.layouts.master')

@section('title', 'Teachers')

@section('content')
<div class="col-md-8">
    <div class="card">
        @can('admin')
        <h5 class="card-header">
            <span>
                <a href="{{ route('pdfexport') }}" class="btn btn-success btn-sm">Summary PDF</a>
                <a href="{{ route('finalreport') }}" class="btn btn-info btn-sm">Detailed PDF</a>
                <a href="{{ route('payment-schedule') }}" class="btn btn-primary btn-sm">Payment</a>
            </span>
        </h5>
        @endcan

        <!-- Form for selecting the week -->
        <div class="card-body">
            <form action="{{ route('attendance.showByWeek') }}" method="GET">
                <div class="form-group d-flex align-items-center">
                    <label for="week" class="mr-2 mb-0">Show Attendance for Week</label>
                    <select name="week" id="week" class="form-control mr-2">
                        <option value="">Select Week</option>
                        @foreach ($weeks as $week)
                            <option value="{{ $week->id }}">Week {{ $week->week_number }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Show</button>
                </div>
            </form>
        </div>
        

        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-sm table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5px;">#</th>
                        <th style="width: 30%;">Teacher</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($users as $user)
                    <tr onclick="window.location='{{ route('attendance.show', $user->id) }}';" style="cursor: pointer;">
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->attendances->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="text-align: center; font-weight: bold; color: red;">Total lessons: {{ number_format($totalAttendances) }}</div>

        <!-- Link to view last 10 remedial entries -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('attendances.latestRecords') }}">View Last 10 Remedial Entries</a>
        </div>
    </div>
</div>
@endsection
