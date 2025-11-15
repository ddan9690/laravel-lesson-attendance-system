@extends('layouts.app')

@section('title', 'Add Student to ' . $stream->name)

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-6">
    Add Student to Stream: {{ $stream->name }}
</h1>

<div class="bg-white shadow rounded p-6 max-w-xl mx-auto"
     x-data="studentForm()"
     x-init="updateTerms()"
     x-cloak>

    <form action="{{ route('classes.streams.students.store', $stream->id) }}" method="POST"
          @submit.prevent="submitting = true; $el.submit()">
        @csrf

        {{-- Name --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Student Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- ADM --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">ADM</label>
            <input type="number" name="adm" value="{{ old('adm') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('adm') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Phone --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
            @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Academic Year / Term --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Joined Academic Year & Term</label>

            {{-- Academic Year --}}
            <div class="mb-2">
                <select name="joined_academic_year_id"
                        x-model="selectedYear"
                        @change="updateTerms()"
                        required
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
                <select name="joined_term_id"
                        x-model="selectedTerm"
                        required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-green-200">
                    <option value="">-- Select Term --</option>
                    <template x-for="term in terms" :key="term.id">
                        <option :value="term.id" x-text="term.name"></option>
                    </template>
                </select>
                @error('joined_term_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit"
                    :disabled="submitting"
                    class="bg-school-green text-white px-6 py-2 rounded hover:bg-green-700 disabled:opacity-50"
                    x-text="submitting ? 'Adding...' : 'Save Student'">
            </button>
            <a href="{{ route('classes.streams.students', $stream->id) }}"
               class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>

    </form>
</div>

{{-- Alpine JS --}}
<script>
    function studentForm() {
        return {
            selectedYear: '',
            selectedTerm: '',
            terms: [],
            allYears: @json($academicYears),
            submitting: false,
            updateTerms() {
                const year = this.allYears.find(y => y.id == this.selectedYear);
                this.terms = year ? year.terms : [];
                this.selectedTerm = '';
            }
        }
    }
</script>
@endsection
