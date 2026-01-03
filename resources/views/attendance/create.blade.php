@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6" x-data="attendanceDropdowns()">

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

            {{-- Tailwind Success Alert --}}
            @if (session('success'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors Alert --}}
            @if ($errors->any())
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('attendance.store') }}" class="bg-white p-6 rounded shadow-lg space-y-6"
                x-ref="attendanceForm" @submit.prevent="confirmSubmit">

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

                {{-- 8-4-4 Curriculum --}}
                <div x-show="curriculumName === '8-4-4'" class="space-y-4">

                    {{-- Form --}}
                    <div>
                        <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Form</label>
                        <select name="form_id" x-model="selectedForm" @change="updateFormStreams()"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                            <option value="" selected></option>
                            <template x-for="form in forms.filter(f => f.curriculum_id == selectedCurriculum)"
                                :key="form.id">
                                <option :value="form.id" x-text="form.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Form Stream --}}
                    <div>
                        <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Stream</label>
                        <select name="form_stream_id" x-model="selectedFormStream"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                            <option value="" selected></option>
                            <template x-for="stream in filteredFormStreams" :key="stream.id">
                                <option :value="stream.id" x-text="stream.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Subject --}}
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

                {{-- CBC Curriculum --}}
                <div x-show="curriculumName === 'CBC'" class="space-y-4">

                    {{-- Grade --}}
                    <div>
                        <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Grade</label>
                        <select name="grade_id" x-model="selectedGrade" @change="updateGradeStreams()"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                            <option value="" selected></option>
                            <template x-for="grade in grades.filter(g => g.curriculum_id == selectedCurriculum)"
                                :key="grade.id">
                                <option :value="grade.id" x-text="grade.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Grade Stream --}}
                    <div>
                        <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Stream</label>
                        <select name="grade_stream_id" x-model="selectedGradeStream"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                            <option value="" selected></option>
                            <template x-for="stream in filteredGradeStreams" :key="stream.id">
                                <option :value="stream.id" x-text="stream.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Learning Area --}}
                    <div>
                        <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Learning
                            Area</label>
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
                    <select name="lesson_id" x-model="selectedLesson" required
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg focus:border-green-800 focus:ring focus:ring-green-200 transition duration-150">
                        <option value="" selected></option>
                        <template x-for="lesson in filteredLessons" :key="lesson.id">
                            <option :value="lesson.id"
                                x-text="lesson.name + ' (' + lesson.start_time + ' - ' + lesson.end_time + ')'"></option>
                        </template>
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
                    <div
                        class="flex flex-wrap gap-4 px-3 py-2 border-2 border-green-600 rounded-b-md shadow-lg mb-2 items-center">
                        <label class="inline-flex items-center space-x-2">
                            <input type="radio" name="status" value="attended" checked
                                class="form-radio text-green-600">
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

                        <button type="button" @click="showNotes = true"
                            class="ml-auto px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">Add
                            Note</button>
                    </div>

                    {{-- Notes Modal --}}
                    <div x-show="showNotes" x-cloak
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white rounded shadow-lg w-full max-w-md p-6">
                            <h3 class="text-lg font-semibold mb-4">Add Note</h3>
                            <textarea name="notes" rows="4" class="w-full border-2 border-green-600 rounded px-3 py-2 mb-4"
                                placeholder="Enter note here..."></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showNotes = false"
                                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                                <button type="button" @click="showNotes = false"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-4">
                    <button type="submit" x-ref="submitBtn"
                        class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-150 shadow-lg">
                        Save Attendance
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function attendanceDropdowns() {
            return {
                selectedCurriculum: null,
                curriculumName: '',
                showNotes: false,

                forms: @json($forms),
                grades: @json($grades),
                formStreams: @json($formStreams),
                gradeStreams: @json($gradeStreams),
                lessons: @json($lessons),

                selectedForm: null,
                selectedFormStream: null,
                filteredFormStreams: [],

                selectedGrade: null,
                selectedGradeStream: null,
                filteredGradeStreams: [],

                selectedLesson: null,
                filteredLessons: [],

                selectCurriculum(id, name) {
                    this.selectedCurriculum = id;
                    this.curriculumName = name;
                    this.selectedForm = null;
                    this.selectedGrade = null;
                    this.selectedFormStream = null;
                    this.selectedGradeStream = null;
                    this.selectedLesson = null;
                    this.filteredFormStreams = [];
                    this.filteredGradeStreams = [];
                    this.filteredLessons = this.lessons.filter(l => l.curriculum_id == id);
                },

                reset() {
                    this.selectedCurriculum = null;
                    this.curriculumName = '';
                    this.showNotes = false;
                    this.selectedForm = null;
                    this.selectedGrade = null;
                    this.selectedFormStream = null;
                    this.selectedGradeStream = null;
                    this.selectedLesson = null;
                    this.filteredFormStreams = [];
                    this.filteredGradeStreams = [];
                    this.filteredLessons = [];
                },

                updateFormStreams() {
                    this.selectedFormStream = null;
                    this.filteredFormStreams = this.formStreams.filter(s => s.form_id == this.selectedForm);
                },

                updateGradeStreams() {
                    this.selectedGradeStream = null;
                    this.filteredGradeStreams = this.gradeStreams.filter(s => s.grade_id == this.selectedGrade);
                },

                confirmSubmit() {
                    let form = this.$refs.attendanceForm;
                    let submitBtn = this.$refs.submitBtn;

                    Swal.fire({
                        title: 'Confirm Submission?',
                        text: "Are you sure you want to save this attendance?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, submit',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitBtn.disabled = true;
                            submitBtn.innerText = 'Submitting...';
                            form.submit();
                        }
                    });
                }
            }
        }
    </script>
@endsection
