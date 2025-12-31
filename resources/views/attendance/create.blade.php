@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6" x-data="{
    selectedCurriculum: null,
    curriculumName: '',
    showNotes: false,
    selectCurriculum(id, name) {
        this.selectedCurriculum = id;
        this.curriculumName = name;
    },
    reset() {
        this.selectedCurriculum = null;
        this.curriculumName = '';
        this.showNotes = false;
    }
}">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-800">Capture Lesson Attendance</h1>
        <a href="{{ route('attendance.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
            Back
        </a>
    </div>

    {{-- Curriculum Selection --}}
    <div x-show="!selectedCurriculum" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($curriculums as $curriculum)
            <div @click="selectCurriculum({{ $curriculum->id }}, '{{ $curriculum->name }}')"
                class="cursor-pointer p-6 border rounded-lg hover:shadow-lg transition bg-green-50">
                <h2 class="text-xl font-semibold text-green-800">{{ $curriculum->name }}</h2>
                <p class="text-sm text-green-700 mt-2">Click to capture attendance</p>
            </div>
        @endforeach
    </div>

    {{-- Attendance Form --}}
    <div x-show="selectedCurriculum" x-cloak class="mt-8">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-green-800">
                Capture Attendance — <span x-text="curriculumName"></span>
            </h2>
            <button @click="reset()" class="text-sm text-red-600 hover:underline">Change Curriculum</button>
        </div>

        <form method="POST" action="{{ route('attendance.store') }}" class="bg-white p-6 rounded shadow-lg space-y-6">
            @csrf

            <input type="hidden" name="curriculum_id" :value="selectedCurriculum">
            <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
            <input type="hidden" name="term_id" value="{{ $term->id }}">

            {{-- Week --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Week</label>
                <select name="week_id" required
                    class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                    <option value="" selected></option>
                    @foreach ($weeks as $week)
                        <option value="{{ $week->id }}">{{ $week->name ?? 'Week ' . $week->id }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 8-4-4 --}}
            <div x-show="curriculumName === '8-4-4'" class="space-y-4">
                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Form</label>
                    <select name="form_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        @foreach ($forms as $form)
                            <template x-if="{{ $form->curriculum_id }} == selectedCurriculum">
                                <option value="{{ $form->id }}">{{ $form->name }}</option>
                            </template>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Stream</label>
                    <select name="form_stream_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        @foreach ($formStreams as $stream)
                            <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Subject</label>
                    <select name="subject_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- CBC --}}
            <div x-show="curriculumName === 'CBC'" class="space-y-4">
                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Grade</label>
                    <select name="grade_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        @foreach ($grades as $grade)
                            <template x-if="{{ $grade->curriculum_id }} == selectedCurriculum">
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            </template>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Stream</label>
                    <select name="grade_stream_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        @foreach ($gradeStreams as $stream)
                            <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Learning Area</label>
                    <select name="learning_area_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        @foreach ($learningAreas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Lesson --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Lesson</label>
                <select name="lesson_id" required
                    class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                    <option value="" selected></option>
                    @foreach ($lessons as $lesson)
                        <option value="{{ $lesson->id }}">{{ $lesson->name }} ({{ $lesson->start_time }} - {{ $lesson->end_time }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Teacher --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Teacher</label>
                <select name="teacher_id" required
                    class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                    <option value="" selected></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status and Notes --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium mb-1">Status</label>
                <div class="flex flex-wrap gap-4 px-3 py-2 border-2 border-green-600 rounded-b-md shadow-lg mb-2 items-center">
                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="status" value="attended" checked class="form-radio text-green-600">
                        <span class="text-green-800 font-medium">Attended</span>
                    </label>
                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="status" value="missed" class="form-radio text-red-600">
                        <span class="text-red-800 font-medium">Missed</span>
                    </label>
                    <label class="inline-flex items-center space-x-2">
                        <input type="radio" name="status" value="makeup" class="form-radio text-yellow-600">
                        <span class="text-yellow-800 font-medium">Makeup</span>
                    </label>

                    <button type="button" @click="showNotes = true" class="ml-auto px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">Add Note</button>
                </div>

                {{-- Notes Modal --}}
                <div x-show="showNotes" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Add Note</h3>
                        <textarea name="notes" rows="4" class="w-full border-2 border-green-600 rounded px-3 py-2 mb-4" placeholder="Enter note here..."></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showNotes = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                            <button type="button" @click="showNotes = false" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-4">
                <button type="submit" class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-150 shadow-lg">
                    Save Attendance
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
