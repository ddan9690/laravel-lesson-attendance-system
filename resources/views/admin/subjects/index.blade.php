@extends('layouts.app')

@section('title', 'Subjects / Learning Areas')

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">Subjects / Learning Areas</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- 8-4-4 Subjects Table --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg p-3">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-xl font-semibold text-school-green">8-4-4 Subjects</h2>
            <a href="{{ route('subjects.create', ['curriculum' => $curricula->firstWhere('name', '8-4-4')->id, 'type' => 'subject']) }}"
               class="bg-school-green text-white px-3 py-1 rounded text-sm hover:bg-green-700">
               Add Subject
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 table-auto text-sm whitespace-nowrap">
                <thead class="bg-school-green text-white">
                    <tr>
                        <th class="py-1 px-2 text-left">#</th>
                        <th class="py-1 px-2 text-left">Name</th>
                        <th class="py-1 px-2 text-left">Short</th>
                        <th class="py-1 px-2 text-left">Code</th>
                        <th class="py-1 px-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($curricula->firstWhere('name', '8-4-4')->subjects ?? [] as $index => $subject)
                        <tr class="border-t">
                            <td class="py-1 px-2">{{ $index + 1 }}</td>
                            <td class="py-1 px-2">{{ $subject->name }}</td>
                            <td class="py-1 px-2">{{ $subject->short }}</td>
                            <td class="py-1 px-2">{{ $subject->code }}</td>
                            <td class="py-1 px-2">
                                <div class="flex space-x-2 items-center">
                                    {{-- Edit --}}
                                    <a href="{{ route('subjects.edit', $subject->id) }}"
                                       class="text-yellow-500 hover:text-yellow-600">
                                       <i class='bx bx-edit text-lg'></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button
                                        x-data
                                        @click.prevent="
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'You will not be able to recover this!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes, delete it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    fetch('{{ route('subjects.destroy', $subject->id) }}', {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json',
                                                        },
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire(
                                                                'Deleted!',
                                                                data.message,
                                                                'success'
                                                            ).then(() => location.reload());
                                                        }
                                                    })
                                                    .catch(err => console.error(err));
                                                }
                                            });
                                        "
                                        class="text-red-500 hover:text-red-600"
                                    >
                                        <i class='bx bx-trash text-lg'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-1 px-2 text-center text-gray-500">No subjects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CBC Learning Areas Table --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg p-3">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-xl font-semibold text-school-green">CBC Learning Areas</h2>
            <a href="{{ route('subjects.create', ['curriculum' => $curricula->firstWhere('name', 'CBC')->id, 'type' => 'learning_area']) }}"
               class="bg-school-green text-white px-3 py-1 rounded text-sm hover:bg-green-700">
               Add Learning Area
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 table-auto text-sm whitespace-nowrap">
                <thead class="bg-school-green text-white">
                    <tr>
                        <th class="py-1 px-2 text-left">#</th>
                        <th class="py-1 px-2 text-left">Name</th>
                        <th class="py-1 px-2 text-left">Short</th>
                        <th class="py-1 px-2 text-left">Code</th>
                        <th class="py-1 px-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($curricula->firstWhere('name', 'CBC')->learningAreas ?? [] as $index => $area)
                        <tr class="border-t">
                            <td class="py-1 px-2">{{ $index + 1 }}</td>
                            <td class="py-1 px-2">{{ $area->name }}</td>
                            <td class="py-1 px-2">{{ $area->short }}</td>
                            <td class="py-1 px-2">{{ $area->code }}</td>
                            <td class="py-1 px-2">
                                <div class="flex space-x-2 items-center">
                                    {{-- Edit --}}
                                    <a href="{{ route('subjects.edit', $area->id) }}"
                                       class="text-yellow-500 hover:text-yellow-600">
                                       <i class='bx bx-edit text-lg'></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button
                                        x-data
                                        @click.prevent="
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'You will not be able to recover this!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Yes, delete it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    fetch('{{ route('subjects.destroy', $area->id) }}', {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json',
                                                        },
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            Swal.fire(
                                                                'Deleted!',
                                                                data.message,
                                                                'success'
                                                            ).then(() => location.reload());
                                                        }
                                                    })
                                                    .catch(err => console.error(err));
                                                }
                                            });
                                        "
                                        class="text-red-500 hover:text-red-600"
                                    >
                                        <i class='bx bx-trash text-lg'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-1 px-2 text-center text-gray-500">No learning areas found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
