@extends('layouts.app')

@section('title',
    $curriculum->name . ' - ' .
    $formOrGrade->name . ' - ' .
    $stream->name . ' - Week ' . $week->name . ' Attendance'
)

@section('content')
<div class="p-4 md:p-6">

    <h1 class="text-xl font-bold text-green-800 mb-4">
        {{ $curriculum->name }}
        — {{ $formOrGrade->name }}
       {{ $stream->name }}
        — Attendance for Week {{ $week->name }}
    </h1>

    {{-- Legend for attendance status --}}
    <div class="mb-4 space-x-4 text-sm">
        <span class="inline-flex items-center space-x-1">
            <i class='bx bx-check-circle text-green-600 text-base'></i>
            <span class="text-gray-700">Attended</span>
        </span>
        <span class="inline-flex items-center space-x-1">
            <i class='bx bx-x-circle text-red-600 text-base'></i>
            <span class="text-gray-700">Missed</span>
        </span>
        <span class="inline-flex items-center space-x-1">
            <i class='bx bx-time text-gray-500 text-base'></i>
            <span class="text-gray-700">Makeup</span>
        </span>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg p-2">
        <table class="min-w-full table-auto text-xs md:text-sm whitespace-nowrap">
            <thead class="bg-green-600 text-white uppercase text-left text-sm">
                <tr>
                    <th class="px-2 py-1">Date</th>
                    <th class="px-2 py-1">Lesson</th>
                    <th class="px-2 py-1">Time</th>
                    <th class="px-2 py-1">{{ $curriculum->name === '8-4-4' ? 'Subject' : 'Learning Area' }}</th>
                    <th class="px-2 py-1">Teacher</th>
                    <th class="px-2 py-1 text-center">Status</th>
                    <th class="px-2 py-1 text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($records->sortByDesc('created_at') as $record)
                    <tr class="border-b even:bg-green-50 hover:bg-green-100" x-data>
                        {{-- Date --}}
                        <td class="px-2 py-1">
                            {{ $record->created_at ? \Carbon\Carbon::parse($record->created_at)->format('d M') : 'N/A' }}
                        </td>

                        {{-- Lesson --}}
                        <td class="px-2 py-1">{{ $record->lesson->name ?? 'N/A' }}</td>

                        {{-- Time --}}
                        <td class="px-2 py-1">
                            {{ $record->lesson && $record->lesson->start_time
                                ? \Carbon\Carbon::parse($record->lesson->start_time)->format('g:ia')
                                : 'N/A' 
                            }}
                        </td>

                        {{-- Subject / Learning Area --}}
                        <td class="px-2 py-1">
                            @if($curriculum->name === '8-4-4')
                                {{ optional($record->subject)->name ?? 'N/A' }}
                            @else
                                {{ optional($record->learningArea)->name ?? 'N/A' }}
                            @endif
                        </td>

                        {{-- Teacher --}}
                        <td class="px-2 py-1">{{ optional($record->teacher)->name ?? 'N/A' }}</td>

                        {{-- Status --}}
                        <td class="px-2 py-1 text-center text-base">
                            @if($record->status === 'attended')
                                <i class='bx bx-check-circle text-green-600'></i>
                            @elseif($record->status === 'missed')
                                <i class='bx bx-x-circle text-red-600'></i>
                            @elseif($record->status === 'makeup')
                                <i class='bx bx-time text-gray-500'></i>
                            @else
                                <i class='bx bx-question-circle text-gray-400'></i>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-2 py-1 text-center">
                            <button
                                @click="Swal.fire({
                                    title: 'Are you sure?',
                                    text: 'This will delete the attendance record!',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((result) => {
                                    if(result.isConfirmed){
                                        axios.delete('{{ route('attendance.destroy', $record->id) }}', {
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                        }).then(response => {
                                            $el.closest('tr').remove();
                                            Swal.fire(
                                                'Deleted!',
                                                'Attendance record has been deleted.',
                                                'success'
                                            );
                                        }).catch(error => {
                                            Swal.fire(
                                                'Error!',
                                                'Something went wrong while deleting.',
                                                'error'
                                            );
                                        });
                                    }
                                })"
                                class="text-red-600 hover:text-red-800 text-base"
                                title="Delete Record"
                            >
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-2 py-2 text-center text-gray-500">
                            No attendance records found for this week.
                            <div class="mt-2">
                                <a href="{{ route('attendance.create') }}"
                                   class="bg-teal-600 text-white px-3 py-1 rounded hover:bg-teal-700 transition text-sm">
                                    Click here to capture a new attendance record
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
