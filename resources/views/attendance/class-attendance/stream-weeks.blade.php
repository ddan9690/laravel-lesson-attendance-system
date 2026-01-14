@extends('layouts.app')

@section('title', $curriculum->name . ' - ' . $formOrGrade->name . ' - ' . $stream->name . ' Attendance')

@section('content')
<div class="p-4 md:p-6">

    <h1 class="text-2xl font-bold text-green-800 mb-4">
        {{ $curriculum->name }} - {{ $formOrGrade->name }} - {{ $stream->name }} Attendance by Week
    </h1>

    @if($weeks->isNotEmpty())
        <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
            <table class="min-w-full table-auto text-sm md:text-base">
                <thead class="bg-green-600 text-white uppercase">
                    <tr>
                        <th class="px-2 py-1 text-left">Week</th>
                        <th class="px-2 py-1 text-center">Attended</th>
                        <th class="px-2 py-1 text-center">Missed</th>
                        <th class="px-2 py-1 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weeks as $week)
                        <tr class="border-b even:bg-green-50 hover:bg-green-100">
                            <td class="px-2 py-1">{{ $week['week_name'] }}</td>
                            <td class="px-2 py-1 text-center font-semibold">{{ $week['attended'] }}</td>
                            <td class="px-2 py-1 text-center font-semibold">{{ $week['missed'] }}</td>
                            <td class="px-2 py-1 text-center">
                                <a href="{{ route('classAttendanceWeekRecords', [
                                        'curriculum' => $curriculum->id,
                                        'formOrGrade' => $formOrGrade->id,
                                        'stream' => $stream->id,
                                        'week' => $week['week_id']
                                    ]) }}"
                                   class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-xs md:text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">No attendance data available for this stream.</p>
    @endif

</div>
@endsection
