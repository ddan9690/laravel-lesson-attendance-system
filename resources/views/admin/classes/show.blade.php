@extends('layouts.app')

@section('title', 'Manage Classes - ' . $curriculum->name)

@section('content')
    <h1 class="text-2xl font-bold text-school-green mb-6">{{ $curriculum->name }} - Classes</h1>

    {{-- Promote All Students Button --}}
    <div class="mb-4">
        <form action="{{ route('students.promoteAll') }}" method="POST"
            onsubmit="return confirm('Are you sure you want to promote all students?');">
            @csrf
            <button type="submit" disabled
                class="bg-blue-500 text-white px-4 py-2 rounded flex items-center space-x-2 opacity-50 cursor-not-allowed">
                <i class='bx bx-up-arrow-alt'></i>
                <span>Move students to next grade</span>
            </button>
        </form>
    </div>

    @php
        $items = $type === 'forms' ? $forms : $grades;
    @endphp

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 table-auto">
            <thead class="bg-school-green text-white">
                <tr>
                    <th class="py-2 px-4 text-left whitespace-nowrap">{{ $type === 'forms' ? 'Form Name' : 'Grade Name' }}
                    </th>
                    <th class="py-2 px-4 text-left whitespace-nowrap">Supervisor</th>
                    <th class="py-2 px-4 text-left whitespace-nowrap"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr class="border-t border-gray-200">
                        <td class="py-2 px-4 whitespace-nowrap">{{ $item->name }}</td>
                        <td class="py-2 px-4 whitespace-nowrap">
                            <form action="{{ route('classes.updateSupervisor') }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="hidden" name="{{ $type === 'forms' ? 'form_id' : 'grade_id' }}"
                                    value="{{ $item->id }}">
                                <select name="supervisor_id" class="border rounded p-1 text-sm mr-2">
                                    <option value="">-- None --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"
                                            {{ $item->class_supervisor_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="bg-school-green text-white px-2 py-1 rounded hover:bg-green-700">Update</button>
                            </form>
                        </td>
                        <td class="py-2 px-4 whitespace-nowrap">
                            <a href="{{ route('classes.streams.showStreams', [
                                'type' => $type === 'forms' ? 'form' : 'grade',
                                'id' => $item->id,
                            ]) }}"
                                class="bg-school-green text-white px-3 py-1 rounded hover:bg-green-700 flex items-center space-x-1">
                                <i class='bx bx-cog'></i>
                                <span>Manage Streams</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-2">
                            No {{ $type }} found for this curriculum.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
@endsection
