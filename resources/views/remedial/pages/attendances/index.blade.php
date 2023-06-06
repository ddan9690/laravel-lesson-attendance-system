@extends('remedial.layouts.master')
@section('title', 'Teachers')
@section('content')
<div class="col-md-8">
    <div class="card">
    @can('super')
    <h5 class="card-header">
        <span>
            <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm">Add New</a>
            <a href="{{ route('pdfexport') }}" class="btn btn-success btn-sm">Summary PDF</a>
            <a href="{{ route('finalreport') }}" class="btn btn-info btn-sm">Detailed PDF</a> <!-- Added button -->
        </span>
    </h5>


    @endcan

        <div class="table-responsive text-nowrap">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th style="width: 5px;">#</th>
                <th style="width: 30%;">Teacher</th>
                <th >Total</th>
                <th >Action</th>

              </tr>
            </thead>
            <tbody class="table-border-bottom-0">

                @foreach ($users as $user)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->attendances->count() }}</td>
                    <td><a href="{{ route('attendance.show', $user->id) }}" class="btn btn-primary btn-sm">View</a></td>
                </tr>
            @endforeach

            </tbody>
          </table>
        </div>
      </div>
</div>
@endsection

