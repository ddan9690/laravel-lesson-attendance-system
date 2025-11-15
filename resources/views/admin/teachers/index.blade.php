@extends('layouts.app')

@section('title', 'Manage Teachers')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-school-green">Teachers</h1>
    <a href="{{ route('teacher.create') }}" class="bg-school-green text-white px-4 py-2 rounded hover:bg-green-700">
        Add Teacher
    </a>
</div>

@if(session('success'))
    <div class="mb-3 p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<table class="min-w-full table-auto text-sm border-collapse">
    <thead class="bg-school-green text-white">
        <tr>
            <th class="px-2 py-1 whitespace-nowrap">#</th>
            <th class="px-2 py-1 whitespace-nowrap">Name</th>
            <th class="px-2 py-1 whitespace-nowrap">Phone</th>
            <th class="px-2 py-1 whitespace-nowrap">Code</th>
            <th class="px-2 py-1 whitespace-nowrap">Role</th>
            <th class="px-2 py-1 whitespace-nowrap">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($teachers as $teacher)
        <tr class="hover:bg-gray-50 border-b" 
            x-data="{
                role: '{{ $teacher->roles->first()?->name ?? '' }}',
                saving: false,
                message: '',
                async updateRole() {
                    this.saving = true;
                    this.message = '';
                    try {
                        const res = await fetch('{{ route('teacher.changeRole') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                user_id: {{ $teacher->id }},
                                role: this.role,
                            }),
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.message = '✅ Updated';
                        } else {
                            this.message = '⚠️ Failed';
                        }
                    } catch (e) {
                        this.message = '❌ Error';
                    }
                    this.saving = false;
                    setTimeout(() => this.message = '', 2000);
                }
            }"
        >
            <td class="px-2 py-1">{{ $loop->iteration }}</td>
            <td class="px-2 py-1">{{ $teacher->name }}</td>
            <td class="px-2 py-1">{{ $teacher->phone }}</td>
            <td class="px-2 py-1">{{ $teacher->code }}</td>
            <td class="px-2 py-1">
                <div class="flex items-center space-x-2">
                    <select x-model="role" @change="updateRole"
                        class="border rounded p-1 text-sm"
                        :disabled="saving">
                        @foreach($roles as $roleValue => $roleLabel)
                            <option value="{{ $roleValue }}">{{ $roleLabel }}</option>
                        @endforeach
                    </select>
                    <span x-text="message" class="text-xs text-gray-500"></span>
                </div>
            </td>
            <td class="px-2 py-1 flex space-x-1">
                <a href="{{ route('teacher.edit', [$teacher->id, $teacher->slug]) }}" 
                   class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 text-xs">
                    Edit
                </a>
                <form action="{{ route('teacher.destroy', [$teacher->id, $teacher->slug]) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">
                        Delete
                    </button>
                </form>
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
