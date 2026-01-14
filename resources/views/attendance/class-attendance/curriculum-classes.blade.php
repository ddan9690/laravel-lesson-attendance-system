@extends('layouts.app')

@section('title', $curriculum->name . ' - ' . $formOrGrade->name . ' Attendance')

@section('content')
<div class="p-4 md:p-6">

    <h1 class="text-2xl font-bold text-green-800 mb-4">
        {{ $curriculum->name }} - {{ $formOrGrade->name }} Attendance
    </h1>

    <div class="mb-6 bg-white shadow rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Overall Attendance Summary</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div class="p-3 bg-green-50 border border-green-200 rounded text-center">
                <p class="text-gray-600">Total Attended</p>
                <p class="text-xl font-bold text-green-800">{{ $summary['total_attended'] }}</p>
            </div>
            <div class="p-3 bg-red-50 border border-red-200 rounded text-center">
                <p class="text-gray-600">Total Missed</p>
                <p class="text-xl font-bold text-red-800">{{ $summary['total_missed'] }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-lg font-semibold text-gray-700 mb-3">Select Stream</h2>

    @if($streams->isNotEmpty())
        <form method="get" action="" id="streamSelectForm" class="mb-6">
            <label for="stream" class="block mb-1 font-medium text-gray-700">Stream</label>
            <select name="stream" id="stream" class="border rounded px-3 py-2 w-full md:w-1/3" onchange="goToStream()">
                <option value="">-- Select Stream --</option>
                @foreach($streams as $stream)
                    <option value="{{ route('classAttendanceByStream', [
                        'curriculum' => $curriculum->id,
                        'formOrGrade' => $formOrGrade->id,
                        'stream' => $stream->id
                    ]) }}">
                        {{ $stream->name }}
                    </option>
                @endforeach
            </select>
        </form>
    @else
        <p class="text-gray-500">No streams found for this {{ $curriculum->name === '8-4-4' ? 'form' : 'grade' }}.</p>
    @endif

    <h2 class="text-lg font-semibold text-gray-700 mt-6 mb-3">Attendance by Week</h2>
    @if(!empty($summary['weeks']))
        <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
            <table class="min-w-full table-auto text-sm md:text-base">
                <thead class="bg-green-600 text-white uppercase">
                    <tr>
                        <th class="px-2 py-1 text-left">Week</th>
                        <th class="px-2 py-1 text-center">Attended</th>
                        <th class="px-2 py-1 text-center">Missed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary['weeks'] as $week)
                        <tr class="border-b even:bg-green-50 hover:bg-green-100">
                            <td class="px-2 py-1">{{ $week['week_name'] }}</td>
                            <td class="px-2 py-1 text-center font-semibold">{{ $week['attended'] }}</td>
                            <td class="px-2 py-1 text-center font-semibold">{{ $week['missed'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">No weekly attendance data available.</p>
    @endif

</div>

<script>
function goToStream() {
    const select = document.getElementById('stream');
    const url = select.value;
    if(url) {
        window.location.href = url;
    }
}
</script>
@endsection
