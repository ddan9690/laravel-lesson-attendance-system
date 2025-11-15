@extends('layouts.app')

@section('title', 'Edit Student: ' . $student->name)

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Edit Student: {{ $student->name }}
</h1>

<div class="bg-white shadow rounded p-6 max-w-xl mx-auto"
     x-data="studentForm()"
     x-init="initTerms()"
     x-cloak>

    <form action="{{ route('classes.streams.students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Student Name</label>
            <input type="text" name="name" value="{{ old('name', $student->name) }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- ADM --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Admission Number (ADM)</label>
            <input type="number" name="adm" value="{{ old('adm', $student->adm) }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('adm') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Phone Number (Optional)</label>
            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Joined Academic Year / Term --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Joined Academic Year & Term</label>

            <div class="flex items-center gap-4 mb-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="joined_type" value="current" x-model="joinedType" class="mr-2">
                    Use Current Active
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="joined_type" value="specify" x-model="joinedType" class="mr-2">
                    Specify
                </label>
            </div>

            <div x-show="joinedType === 'specify'" x-cloak class="space-y-2 mt-2">

                {{-- Academic Year --}}
                <div>
                    <select name="joined_academic_year_id" x-model="selectedYear" @change="updateTerms()" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                        <option value="">-- Select Academic Year --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                    @error('joined_academic_year_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Term --}}
                <div>
                    <select name="joined_term_id" x-model="selectedTerm" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                        <option value="">-- Select Term --</option>
                        <template x-for="term in terms" :key="term.id">
                            <option :value="term.id" x-text="term.name"></option>
                        </template>
                    </select>
                    @error('joined_term_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit"
                    class="bg-school-green text-white px-6 py-2 rounded hover:bg-green-700">Update Student</button>
            <a href="{{ route('classes.streams.students', $student->grade_stream_id) }}"
               class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>

    </form>
</div>

{{-- Alpine JS --}}
<script>
    function studentForm() {
        return {
            joinedType: 'specify',
            selectedYear: '{{ old('joined_academic_year_id', $student->joined_academic_year_id) }}',
            selectedTerm: '{{ old('joined_term_id', $student->joined_term_id) }}',
            terms: [],
            allYears: @json($academicYears),
            initTerms() {
                if (this.selectedYear) this.updateTerms();
            },
            updateTerms() {
                const year = this.allYears.find(y => y.id == this.selectedYear);
                this.terms = year ? year.terms : [];
                if (!this.terms.find(t => t.id == this.selectedTerm)) {
                    this.selectedTerm = '';
                }
            }
        }
    }
</script>
@endsection
