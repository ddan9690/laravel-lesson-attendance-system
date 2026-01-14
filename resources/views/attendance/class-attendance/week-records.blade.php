@extends('layouts.app')

@section('title',
    $curriculum->name . ' - ' .
    $formOrGrade->name . ' - ' .
    $stream->name . ' - Week ' . $week->name . ' Attendance'
)

@section('content')
<div class="p-4 md:p-6">

    <h1 class="text-2xl font-bold text-green-800 mb-4">
        {{ $curriculum->name }}
        —
        {{ $formOrGrade->name }}
        —
        Stream {{ $stream->name }}
        —
        Attendance for Week {{ $week->name }}
    </h1>

    @if($records->isNotEmpty())
        <div class="overflow-x-auto bg-white shadow rounded-lg p-4">
            <table class="min-w-full table-auto text-sm md:text-base">
                <thead class="bg-green-600 text-white uppercase">
                    <tr>
                        <th class="px-3 py-2 text-left">Date</th>
                        <th class="px-3 py-2 text-left">Lesson</th>
                        <th class="px-3 py-2 text-left">Start Time</th>
                        <th class="px-3 py-2 text-left">
                            {{ $curriculum->name === '8-4-4' ? 'Subject' : 'Learning Area' }}
                        </th>
                        <th class="px-3 py-2 text-left">Teacher</th>
                        <th class="px-3 py-2 text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($records as $record)
                        <tr class="border-b even:bg-green-50 hover:bg-green-100">

                            {{-- Attendance record creation date --}}
                            <td class="px-3 py-2">
                                {{ $record->created_at?->format('d M Y') ?? 'N/A' }}
                            </td>

                            <td class="px-3 py-2">
                                {{ $record->lesson->name ?? 'N/A' }}
                            </td>

                            <td class="px-3 py-2">
                                {{ $record->lesson->start_time ?? 'N/A' }}
                            </td>

                            <td class="px-3 py-2">
                                @if($curriculum->name === '8-4-4')
                                    {{ optional($record->subject)->name ?? 'N/A' }}
                                @else
                                    {{ optional($record->learningArea)->name ?? 'N/A' }}
                                @endif
                            </td>

                            <td class="px-3 py-2">
                                {{ optional($record->teacher)->name ?? 'N/A' }}
                            </td>

                            <td class="px-3 py-2 text-center font-semibold capitalize
                                @if($record->status === 'attended') text-green-700
                                @elseif($record->status === 'missed') text-red-700
                                @else text-yellow-700
                                @endif
                            ">
                                {{ $record->status }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 mt-4">
            No attendance records found for this week.
        </p>
    @endif

</div>
@endsection
