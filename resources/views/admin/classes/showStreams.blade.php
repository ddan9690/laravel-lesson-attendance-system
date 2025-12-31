@extends('layouts.app')

@section('title', 'Streams in ' . $item->name)

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Streams in {{ $type === 'form' ? 'Form' : 'Grade' }}: {{ $item->name }}
    <span class="text-sm font-normal text-gray-600">({{ $curriculum->name }} Curriculum)</span>
</h1>

<div class="mb-6 flex gap-2">
    <a href="{{ route('classes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        Back to Classes
    </a>

    <a href="{{ route('classes.streams.create', ['type' => $type, 'id' => $item->id]) }}"
       class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">
        + Add {{ $type === 'form' ? 'Form' : 'Grade' }} Stream
    </a>
</div>

@if($streams->count() > 0)
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 table-auto">
        <thead class="bg-school-green text-white">
            <tr>
                <th class="py-2 px-4 text-left whitespace-nowrap">Stream Name</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Class Teacher</th>
                <th class="py-2 px-4 text-left whitespace-nowrap">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($streams as $stream)
            <tr x-data="{
                    teacher: '{{ $stream->class_teacher_id ?? '' }}',
                    saving: false,
                    async updateTeacher() {
                        const result = await Swal.fire({
                            title: 'Are you sure?',
                            text: 'This will update the class teacher.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, update it!',
                            cancelButtonText: 'Cancel'
                        });
                        if (!result.isConfirmed) return;

                        this.saving = true;
                        try {
                            const res = await fetch('{{ route('classes.streams.updateTeacher', ['stream' => $stream->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    stream_id: {{ $stream->id }},
                                    teacher_id: this.teacher || null
                                })
                            });

                            const data = await res.json();
                            if (data.success) {
                                Swal.fire('Updated!', data.message, 'success');
                            } else {
                                Swal.fire('Error', data.message || 'Update failed', 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'An unexpected error occurred', 'error');
                        }
                        this.saving = false;
                    }
                }">
                <td class="py-2 px-4 border-t border-gray-200 whitespace-nowrap">{{ $stream->name }}</td>
                <td class="py-2 px-4 border-t border-gray-200 whitespace-nowrap">
                    <select x-model="teacher" @change="updateTeacher"
                            class="border rounded p-1 text-sm w-full" :disabled="saving">
                        <option value="">-- None --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                {{ $stream->class_teacher_id == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="py-2 px-4 border-t border-gray-200 whitespace-nowrap flex space-x-2">
                    {{-- Manage Streams --}}
                    <a href="{{ route('classes.streams.showStreams', [
                        'type' => $type,
                        'id' => $item->id
                    ]) }}"
                        class="bg-school-green text-white px-3 py-1 rounded hover:bg-green-700 flex items-center space-x-1">
                        <i class='bx bx-cog'></i>
                        <span>Manage Streams</span>
                    </a>

                    {{-- New: Manage Subject/Learning Area Teachers --}}
                    <a href="{{ route('classes.assignments.manage', [
                        'curriculum' => strtolower($curriculum->name),
                        'id' => $item->id
                    ]) }}"
                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 flex items-center space-x-1">
                        <i class='bx bx-user'></i>
                        <span>
                            {{ $type === 'form' ? 'Manage Subject Teachers' : 'Manage Learning Area Teachers' }}
                        </span>
                    </a>

                    @if($type === 'grade')
                        <a href="{{ route('classes.streams.students', ['stream' => $stream->id]) }}"
                           class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800 text-sm">
                            View Students
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-white shadow rounded p-6 text-center">
    <p class="text-gray-500 text-lg">No streams found for this {{ $type === 'form' ? 'form' : 'grade' }}.</p>
    <a href="{{ route('classes.streams.create', ['type' => $type, 'id' => $item->id]) }}"
       class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700 mt-3 inline-block">
       Create First Stream
    </a>
</div>
@endif
@endsection
