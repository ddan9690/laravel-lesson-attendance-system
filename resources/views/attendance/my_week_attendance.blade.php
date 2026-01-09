@extends('layouts.app')

@section('title', 'Lesson Attendance for Week')

@section('content')
<div class="p-4 md:p-6">

    <!-- Teacher Name as main heading -->
    <h1 class="text-3xl font-bold mb-2 text-green-800">
        {{ $user->name ?? 'Teacher' }}
    </h1>

    <!-- Week / Academic Year / Term as subheading -->
    <h2 class="text-xl font-semibold mb-6 text-gray-700">
        Lesson Attendance for Week: {{ $week->name ?? 'N/A' }} |
        {{ $currentYear->year ?? 'N/A' }} / {{ $currentTerm->name ?? 'N/A' }}
    </h2>

    @if($attendance->isNotEmpty())
        @foreach($attendance as $curriculumName => $records)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">{{ $curriculumName }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto text-sm md:text-base">
                        <thead class="bg-green-600 text-white text-xs md:text-sm uppercase">
                            <tr>
                                <th class="py-2 px-3 text-left">Lesson</th>
                                <th class="py-2 px-3 text-left">Time</th>
                                <th class="py-2 px-3 text-left">Class</th>
                                <th class="py-2 px-3 text-left">Stream</th>
                                <th class="py-2 px-3 text-left text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr class="border-b border-green-200 even:bg-green-50 hover:bg-green-100 transition-colors">
                                    <td class="py-2 px-3 whitespace-nowrap">{{ $record->lesson->name ?? 'N/A' }}</td>
                                    
                                    <td class="py-2 px-3 whitespace-nowrap">
                                        {{ $record->lesson->start_time ? \Carbon\Carbon::parse($record->lesson->start_time)->format('g:ia') : 'N/A' }}
                                    </td>

                                    <td class="py-2 px-3 whitespace-nowrap">
                                        {{ $record->grade->name ?? $record->form->name ?? 'N/A' }}
                                    </td>

                                    <td class="py-2 px-3 whitespace-nowrap">
                                        {{ $record->gradeStream->name ?? $record->formStream->name ?? 'N/A' }}
                                    </td>

                                    <td class="py-2 px-3 whitespace-nowrap font-semibold text-center">
                                        @if($record->status === 'attended')
                                            <i class="bx bx-check-circle text-green-600 text-lg"></i>
                                        @elseif($record->status === 'missed')
                                            <i class="bx bx-x-circle text-red-600 text-lg"></i>
                                        @elseif($record->status === 'make_up')
                                            <i class="bx bx-time-five text-gray-500 text-lg"></i>
                                        @else
                                            <span class="text-gray-500">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">No attendance records found for this week.</p>
    @endif

</div>
@endsection
