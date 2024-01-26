@extends('remedial.layouts.master')
@section('title', 'Teachers')
@section('content')
<div class="col-md-8">
    <div class="card">
    @can('super')
    <h5 class="card-header">
        <span>

            <a href="{{ route('pdfexport') }}" class="btn btn-success btn-sm">Summary PDF</a>
            <a href="{{ route('finalreport') }}" class="btn btn-info btn-sm">Detailed PDF</a> <!-- Added button -->
            <a href="{{ route('payment-schedule') }}" class="btn btn-primary btn-sm">Payment</a> <!-- Added button -->
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
</div>
@endsection
