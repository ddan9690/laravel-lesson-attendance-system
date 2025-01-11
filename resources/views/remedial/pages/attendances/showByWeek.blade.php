@extends('remedial.layouts.master')

@section('title', 'Attendance for Week')

@section('content')
<div class="col-md-8">
    <div class="card">
        <!-- Form for selecting the week -->
        <div class="card-body">
            <form action="{{ route('attendance.showByWeek') }}" method="GET">
                <div class="form-group d-flex align-items-center">
                    <label for="week" class="mr-2 mb-0">Show Attendance for Week</label>
                    <select name="week" id="week" class="form-control mr-2" style="width: auto;">
                        <option value="">Select Week</option>
                        @foreach ($weeks as $week)
                            <option value="{{ $week->id }}" 
                                    @if($week->id == $selectedWeek) selected @endif>
                                Week {{ $week->week_number }} 
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Show</button>
                </div>
            </form>
        </div>

        <!-- Display attendance table if a week is selected -->
        @if ($selectedWeek)
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-center mb-0">Remedial for Week {{ $selectedWeek }}</h4>
                    <div class="d-flex">
                        <!-- PDF download button -->
                        <a href="{{ route('attendance.downloadPdf', $selectedWeek) }}" class="btn btn-sm btn-danger ml-2">Download PDF</a>
                    </div>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-success ml-2">Go Back</a>
                </div>

                <div class="table-responsive text-nowrap mt-3">
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
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->attendances->count() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="text-align: center; font-weight: bold;">Total lessons for week {{ $selectedWeek }}: {{ number_format($totalAttendances) }}</div>
            </div>
        @endif

    </div>
</div>
@endsection
