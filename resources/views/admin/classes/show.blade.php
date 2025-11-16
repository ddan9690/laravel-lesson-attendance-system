@extends('layouts.app')

@section('title', 'Manage Classes - ' . $curriculum->name)

@section('content')
    <h1 class="text-2xl font-bold text-school-green mb-6">{{ $curriculum->name }} - Classes</h1>

    @can('promote_students')
    <div class="mb-4" x-data>
        <form id="promoteForm" action="{{ route('students.promoteAll') }}" method="POST" @submit.prevent="submitPromotion">
            @csrf

            {{-- Promote button (always enabled) --}}
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center space-x-2">
                <i class='bx bx-up-arrow-alt'></i>
                <span>Move students to next grade</span>
            </button>
        </form>
    </div>
    @endcan

    @php
        $items = $type === 'forms' ? $forms : $grades;
    @endphp

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 table-auto">
            <thead class="bg-school-green text-white">
                <tr>
                    <th class="py-2 px-4 text-left whitespace-nowrap">{{ $type === 'forms' ? 'Form Name' : 'Grade Name' }}</th>
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
                                <input type="hidden" name="{{ $type === 'forms' ? 'form_id' : 'grade_id' }}" value="{{ $item->id }}">
                                <select name="supervisor_id" class="border rounded p-1 text-sm mr-2">
                                    <option value="">-- None --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $item->class_supervisor_id == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-school-green text-white px-2 py-1 rounded hover:bg-green-700">Update</button>
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

    {{-- SweetAlert Promotion Script --}}
    <script>
        function submitPromotion(event) {
            Swal.fire({
                title: 'Confirm Promotion',
                html: `
                    <p class="text-left">
                        This is a <strong>critical operation</strong>.<br>
                        All active students will be promoted to the next grade.<br>
                        Grade 12 students will be graduated.<br><br>
                        Type <strong>PROMOTE</strong> to confirm.
                    </p>`,
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Type PROMOTE to confirm',
                inputValidator: (value) => {
                    if (!value || value.toUpperCase() !== 'PROMOTE') {
                        return 'You must type PROMOTE to proceed!';
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Yes, Promote!',
                cancelButtonText: 'Cancel',
                focusConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('promoteForm').submit();
                }
            });
        }

        // Show success SweetAlert if session has 'success'
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Show error SweetAlert if session has 'error'
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Sorry!',
                text: '{{ session('error') }}',
                timer: 4000,
                showConfirmButton: true
            });
        @endif
    </script>
@endsection
