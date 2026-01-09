@extends('layouts.app')

@section('title', 'Teacher Week Attendance')

@section('content')
<div class="p-4 md:p-6">

    <!-- Header -->
    <h1 class="text-2xl font-bold text-green-800 mb-2">
        {{ $teacher->name ?? 'Teacher' }} - Attendance for Week {{ $week->name ?? 'Week' }}
    </h1>

    <h2 class="text-lg text-gray-700 mb-4">
         {{ $currentYear->year ?? 'N/A' }} |
        Term: {{ $currentTerm->name ?? 'N/A' }}
    </h2>

    @if($attendanceRecords->isNotEmpty())
        <div class="bg-white shadow rounded-lg p-3 overflow-x-auto">
            <table class="min-w-full table-fixed text-sm md:text-base">
                <thead class="bg-green-600 text-white uppercase">
                    <tr>
                        <th class="px-2 py-1 text-left">Date</th>
                        <th class="px-2 py-1 text-left">CURR</th>
                        <th class="px-2 py-1 text-left">Lesson</th>
                        <th class="px-2 py-1 text-left">LA/SUB</th>
                        <th class="px-2 py-1 text-left">Time</th>
                        <th class="px-2 py-1 text-center">Status</th>
                        <th class="px-2 py-1 text-left">CB</th>
                        <th class="px-2 py-1 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceRecords as $record)
                        @php
                            $date = $record->lesson->date ?? $record->created_at;
                           
                            $formattedDate = \Carbon\Carbon::parse($date)->format('D d M y');

                            $statusIcon = match($record->status) {
                                'attended' => '<i class="bx bx-check-circle text-green-700 text-lg"></i>',
                                'missed'   => '<i class="bx bx-x-circle text-red-600 text-lg"></i>',
                                'makeup'   => '<i class="bx bx-refresh text-gray-500 text-lg"></i>',
                                default    => '',
                            };

                            $capturedByName = $record->capturedBy->name ?? 'N/A';
                            $initials = collect(explode(' ', $capturedByName))
                                        ->map(fn($n) => strtoupper(substr($n,0,1)))
                                        ->join('');

                            $curriculumName = $record->curriculum->name ?? 'N/A';
                        @endphp
                        <tr class="border-b border-green-200 even:bg-green-50 hover:bg-green-100">
                            <td class="px-2 py-1 whitespace-nowrap">{{ $formattedDate }}</td>
                            <td class="px-2 py-1 whitespace-nowrap">{{ $curriculumName }}</td>
                            <td class="px-2 py-1 whitespace-nowrap">{{ $record->lesson->name ?? 'N/A' }}</td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ $record->lesson->learningArea->name ?? $record->lesson->subject->name ?? 'N/A' }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">
                                {{ $record->lesson->start_time ? \Carbon\Carbon::parse($record->lesson->start_time)->format('g:ia') : 'N/A' }}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap text-center">
                                {!! $statusIcon !!}
                            </td>
                            <td class="px-2 py-1 whitespace-nowrap">{{ $initials }}</td>
                            <td class="px-2 py-1 text-center">
                                <button 
                                    onclick="deleteAttendance({{ $record->id }})" 
                                    class="bg-red-600 text-white px-2 py-1 rounded shadow hover:bg-red-700 text-xs">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 mt-4 text-sm">
            No attendance records found for this week.
        </p>
    @endif

</div>

<script>
    function deleteAttendance(id) {
        Swal.fire({
            title: 'Confirm Deletion?',
            text: "Are you sure you want to delete this attendance record?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed) {
                fetch(`/attendance/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message ?? 'Failed to delete.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error!', 'Something went wrong.', 'error'));
            }
        });
    }
</script>
@endsection
