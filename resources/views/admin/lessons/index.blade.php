@extends('layouts.app')

@section('title', 'Lessons')

@section('content')
    <h1 class="text-2xl font-bold text-school-green mb-6">Lessons</h1>

    {{-- Success Alert --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 8-4-4 Lessons --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg p-3">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold text-school-green">8-4-4 Lessons</h2>
                @can('lesson_capture')
                    <a href="{{ route('lessons.create', ['curriculum' => '8-4-4']) }}"
                        class="bg-school-green text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        Add Lesson
                    </a>
                @endcan
            </div>

            <table class="min-w-full border border-gray-200 table-auto text-sm whitespace-nowrap">
                <thead class="bg-school-green text-white">
                    <tr>
                        <th class="py-1 px-2 text-left">#</th>
                        <th class="py-1 px-2 text-left">Lesson</th>
                        <th class="py-1 px-2 text-left">Start</th>
                        <th class="py-1 px-2 text-left">End</th>
                        @canany(['lesson_edit', 'lesson_delete'])
                            <th class="py-1 px-2 text-left">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse($curriculaLessons['8-4-4'] ?? [] as $index => $lesson)
                        <tr>
                            <td class="py-1 px-2">{{ $index + 1 }}</td>
                            <td class="py-1 px-2">{{ $lesson->name }}</td>
                            <td class="py-1 px-2">{{ \Carbon\Carbon::parse($lesson->start_time)->format('g:i a') }}</td>
                            <td class="py-1 px-2">{{ \Carbon\Carbon::parse($lesson->end_time)->format('g:i a') }}</td>
                            @canany(['lesson_edit', 'lesson_delete'])
                                <td class="py-1 px-2 flex space-x-2">
                                    @can('lesson_edit')
                                        <a href="{{ route('lessons.edit', $lesson->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                    @endcan
                                    @can('lesson_delete')
                                        <form class="delete-lesson" action="{{ route('lessons.destroy', $lesson->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-1 px-2 text-center text-gray-500">No lessons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- CBC Lessons --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg p-3">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold text-school-green">CBC Lessons</h2>
                @can('lesson_capture')
                    <a href="{{ route('lessons.create', ['curriculum' => 'CBC']) }}"
                        class="bg-school-green text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        Add Lesson
                    </a>
                @endcan
            </div>

            <table class="min-w-full border border-gray-200 table-auto text-sm whitespace-nowrap">
                <thead class="bg-school-green text-white">
                    <tr>
                        <th class="py-1 px-2 text-left">#</th>
                        <th class="py-1 px-2 text-left">Lesson</th>
                        <th class="py-1 px-2 text-left">Start</th>
                        <th class="py-1 px-2 text-left">End</th>
                        @canany(['lesson_edit', 'lesson_delete'])
                            <th class="py-1 px-2 text-left">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse($curriculaLessons['CBC'] ?? [] as $index => $lesson)
                        <tr>
                            <td class="py-1 px-2">{{ $index + 1 }}</td>
                            <td class="py-1 px-2">{{ $lesson->name }}</td>
                            <td class="py-1 px-2">{{ $lesson->start_time }}</td>
                            <td class="py-1 px-2">{{ $lesson->end_time }}</td>
                            @canany(['lesson_edit', 'lesson_delete'])
                                <td class="py-1 px-2 flex space-x-2">
                                    @can('lesson_edit')
                                        <a href="{{ route('lessons.edit', $lesson->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                    @endcan
                                    @can('lesson_delete')
                                        <form class="delete-lesson" action="{{ route('lessons.destroy', $lesson->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-1 px-2 text-center text-gray-500">No lessons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- SweetAlert Delete Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-lesson');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // prevent normal submit

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // submit the form if confirmed
                        }
                    });
                });
            });
        });
    </script>

    {{-- SweetAlert Success Toast --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
@endsection
