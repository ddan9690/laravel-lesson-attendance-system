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

    <div style="text-align: center; font-weight: bold; color: red;">Total lessons: {{ $totalAttendances }}</div>

    <!-- Link to view last 10 remedial entries -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('attendances.latestRecords') }}">View Last 10 Remedial Entries</a>
    </div>
</div>
@endsection
