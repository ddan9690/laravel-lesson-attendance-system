@extends('layouts.app')

@section('title', 'Students in ' . $stream->name)

@section('content')
    <h1 class="text-2xl font-bold text-school-green mb-6">
        Students in Stream: {{ $stream->name }}
    </h1>

    <p class="text-red-500 text-xs mb-6">
        {{ $stream->id }}
    </p>

    <div class="mb-4">

        @if ($type === 'grade')
            <a href="{{ route('classes.streams.students.create', $stream->id) }}"
                class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">
                + Add Student
            </a>
            @can('import_students')
                <a href="{{ route('admin.students.import.grade', $stream->id) }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 ml-2">
                    Import from Excel
                </a>
            @endcan
        @endif

        <a href="{{ route('classes.streams.showStreams', ['type' => $type, 'id' => $item->id]) }}"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">
            Back to Streams
        </a>
    </div>

    @if ($type === 'grade')
        <div class="bg-white shadow rounded p-4 overflow-x-auto">

            {{-- Updated table with DataTables --}}
            <table class="datatable min-w-full border border-gray-200 table-auto whitespace-nowrap text-sm">
                <thead class="bg-school-green text-white text-xs">
                    <tr>
                        <th class="py-1 px-2 text-left w-6">#</th>
                        <th class="py-1 px-2 text-left w-20">ADM</th>
                        <th class="py-1 px-2 text-left">Name</th>
                        <th class="py-1 px-2 text-left">Phone</th>

                        {{-- NEW Columns --}}
                        <th class="py-1 px-2 text-left">Year Joined</th>
                        <th class="py-1 px-2 text-left">Term Joined</th>

                        <th class="py-1 px-2 w-32">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $index => $student)
                        <tr class="border-t text-xs">
                            <td class="py-1 px-2">{{ $index + 1 }}</td>
                            <td class="py-1 px-2">{{ $student->adm }}</td>
                            <td class="py-1 px-2">{{ $student->name }}</td>
                            <td class="py-1 px-2">{{ $student->phone ?? 'N/A' }}</td>

                            {{-- NEW Data --}}
                            <td class="py-1 px-2">
                                {{ $student->joinedAcademicYear->year ?? 'N/A' }}
                            </td>

                            <td class="py-1 px-2">
                                {{ $student->joinedTerm->name ?? 'N/A' }}
                            </td>

                            <td class="py-1 px-2 whitespace-nowrap flex gap-1">

                                {{-- Edit --}}
                                <a href="{{ route('classes.streams.students.edit', $student->id) }}"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs">
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <button @click="confirmDelete({{ $student->id }})"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-xs">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-2 px-2 text-center text-gray-500 text-xs">
                                No students found in this stream.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- Alpine + SweetAlert --}}
        <script>
            function confirmDelete(studentId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the student!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete!'
                }).then((result) => {

                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/classes/stream/students/${studentId}`;

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                })
            }

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        </script>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p class="font-semibold">Student Management Not Available</p>
            <p>Student management is only available for CBC curriculum (Grades). This is an 8-4-4 Form which handles streams
                only.</p>
        </div>
    @endif
@endsection
