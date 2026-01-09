@extends('layouts.app')

@section('title', 'Manage Teachers')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-school-green">Teachers</h1>
    <a href="{{ route('teacher.create') }}" class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">
        Add Teacher
    </a>
</div>

@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@endif

<table class="min-w-full table-auto text-sm border-collapse">
    <thead class="bg-school-green text-white">
        <tr>
            <th class="px-2 py-1 whitespace-nowrap">#</th>
            <th class="px-2 py-1 whitespace-nowrap">Name</th>
            <th class="px-2 py-1 whitespace-nowrap">Phone</th>
            <th class="px-2 py-1 whitespace-nowrap">Code</th>
            @can('manage_roles')
                <th class="px-2 py-1 whitespace-nowrap">Role</th>
            @endcan
            <th class="px-2 py-1 whitespace-nowrap">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($teachers as $teacher)
            <tr class="hover:bg-gray-50 border-b" x-data="{
                role: '{{ $teacher->roles->first()?->name ?? '' }}',
                saving: false,
                confirmRoleChange(newRole) {
                    if ('{{ $teacher->email }}' === 'dancanokeyo08@gmail.com') {
                        toastr.warning('Cannot change role of this user.');
                        this.role = '{{ $teacher->roles->first()?->name ?? '' }}';
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Change role to ' + newRole + '?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.updateRole(newRole);
                        } else {
                            this.role = '{{ $teacher->roles->first()?->name ?? '' }}';
                        }
                    });
                },
                async updateRole(newRole) {
                    this.saving = true;
                    try {
                        const res = await fetch('{{ route('teacher.changeRole') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                user_id: {{ $teacher->id }},
                                role: newRole,
                            }),
                        });
                        const data = await res.json();
                        if(data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message || 'Failed to update role.');
                            this.role = '{{ $teacher->roles->first()?->name ?? '' }}';
                        }
                    } catch (e) {
                        toastr.error('An error occurred.');
                        this.role = '{{ $teacher->roles->first()?->name ?? '' }}';
                    }
                    this.saving = false;
                }
            }">
                <td class="px-2 py-1">{{ $loop->iteration }}</td>
                <td class="px-2 py-1">{{ $teacher->name }}</td>
                <td class="px-2 py-1">{{ $teacher->phone }}</td>
                <td class="px-2 py-1">{{ $teacher->code }}</td>

                @can('manage_roles')
                    <td class="px-2 py-1">
                        @if($teacher->email === 'dancanokeyo08@gmail.com')
                            <span class="text-gray-500">-</span>
                        @else
                            <select 
                                x-model="role" 
                                @change="confirmRoleChange($event.target.value)" 
                                class="border rounded p-1 text-sm" 
                                :disabled="saving"
                            >
                                @foreach ($roles as $roleValue => $roleLabel)
                                    <option value="{{ $roleValue }}">{{ $roleLabel }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>
                @endcan

                <td class="px-2 py-1 flex space-x-1">
                    @if($teacher->email === 'dancanokeyo08@gmail.com')
                        <span class="text-gray-500 text-xs">-</span>
                    @else
                        <a href="{{ route('teacher.edit', [$teacher->id, $teacher->slug]) }}"
                            class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs">
                            Edit
                        </a>
                        <form action="{{ route('teacher.destroy', [$teacher->id, $teacher->slug]) }}" method="POST"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                                Delete
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-gray-500 py-2">No teachers found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
