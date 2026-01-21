@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6" x-data="attendanceDropdowns()">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-green-800">Capture Lesson Attendance</h1>
       
    </div>

    {{-- Curriculum Selection --}}
    <div x-show="!selectedCurriculum" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($curriculums as $curriculum)
            <div
                @click="selectCurriculum({{ $curriculum->id }}, '{{ $curriculum->name }}')"
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
            <button @click="reset()" class="text-sm text-red-600 hover:underline">
                Change Curriculum
            </button>
        </div>

        <form method="POST"
              action="{{ route('attendance.store') }}"
              class="bg-white p-6 rounded shadow-lg space-y-6"
              x-ref="attendanceForm"
              @submit.prevent="confirmSubmit">

            @csrf

            <input type="hidden" name="curriculum_id" :value="selectedCurriculum">
            <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
            <input type="hidden" name="term_id" value="{{ $term->id }}">

            {{-- 1. Teacher --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Teacher</label>
                <select name="teacher_id" required
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                    <option value="">-- Select Teacher --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Subject / Learning Area --}}
            <div x-show="curriculumName === '8-4-4'">
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Subject</label>
                <select name="subject_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                    <option value="">-- Select Subject --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="curriculumName === 'CBC'">
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Learning Area</label>
                <select name="learning_area_id"
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                    <option value="">-- Select Learning Area --</option>
                    @foreach ($learningAreas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 3. Form / Grade --}}
            <div x-show="curriculumName === '8-4-4'" class="space-y-4">

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Form</label>
                    <select name="form_id"
                            x-model="selectedForm"
                            @change="updateFormStreams()"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                        <option value="">-- Select Form --</option>
                        <template x-for="form in forms.filter(f => f.curriculum_id == selectedCurriculum)" :key="form.id">
                            <option :value="form.id" x-text="form.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Stream</label>
                    <select name="form_stream_id"
                            x-model="selectedFormStream"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                        <option value="">-- Select Stream --</option>
                        <template x-for="stream in filteredFormStreams" :key="stream.id">
                            <option :value="stream.id" x-text="stream.name"></option>
                        </template>
                    </select>
                </div>

            </div>

            <div x-show="curriculumName === 'CBC'" class="space-y-4">

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Grade</label>
                    <select name="grade_id"
                            x-model="selectedGrade"
                            @change="updateGradeStreams()"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                        <option value="">-- Select Grade --</option>
                        <template x-for="grade in grades.filter(g => g.curriculum_id == selectedCurriculum)" :key="grade.id">
                            <option :value="grade.id" x-text="grade.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Stream</label>
                    <select name="grade_stream_id"
                            x-model="selectedGradeStream"
                            class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                        <option value="">-- Select Stream --</option>
                        <template x-for="stream in filteredGradeStreams" :key="stream.id">
                            <option :value="stream.id" x-text="stream.name"></option>
                        </template>
                    </select>
                </div>

            </div>

            {{-- 4. Lesson --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Lesson</label>
                <select name="lesson_id" x-model="selectedLesson" required
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                    <option value="">-- Select Lesson --</option>
                    <template x-for="lesson in filteredLessons" :key="lesson.id">
                        <option :value="lesson.id"
                                x-text="lesson.name + ' (' + lesson.start_time_formatted + ' - ' + lesson.end_time_formatted + ')'">
                        </option>
                    </template>
                </select>
            </div>

            {{-- 5. Week --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium">Week</label>
                <select name="week_id" required
                        class="mt-1 block w-full px-3 py-2 rounded-b-md border-2 border-green-600 shadow-lg">
                    <option value="">-- Select Week --</option>
                    @foreach ($weeks as $week)
                        <option value="{{ $week->id }}">
                            {{ $week->name ?? 'Week '.$week->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 6. Status --}}
            <div>
                <label class="block bg-green-600 text-white px-3 py-1 rounded-t-md font-medium mb-1">Status</label>
                <div class="flex gap-4 px-3 py-2 border-2 border-green-600 rounded-b-md shadow-lg">
                    <label><input type="radio" name="status" value="attended" checked> Attended</label>
                    <label><input type="radio" name="status" value="missed"> Missed</label>
                    <label><input type="radio" name="status" value="makeup"> Makeup</label>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    x-ref="submitBtn"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 shadow">
                Save Attendance
            </button>

        </form>
    </div>
</div>

{{-- TOASTR (TOP CENTER) --}}
<script>
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-center",
    timeOut: "4000"
};

@if (session('success'))
    toastr.success("{{ session('success') }}");
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
@endif
</script>

<script>
function attendanceDropdowns() {
    return {
        selectedCurriculum: null,
        curriculumName: '',

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
            this.filteredLessons = this.lessons.filter(l => l.curriculum_id == id);
        },

        reset() {
            this.selectedCurriculum = null;
            this.curriculumName = '';
            this.filteredLessons = [];
        },

        updateFormStreams() {
            this.filteredFormStreams = this.formStreams.filter(s => s.form_id == this.selectedForm);
        },

        updateGradeStreams() {
            this.filteredGradeStreams = this.gradeStreams.filter(s => s.grade_id == this.selectedGrade);
        },

        confirmSubmit() {
            Swal.fire({
                title: 'Confirm Submission?',
                text: 'Are you sure you want to save this attendance?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$refs.submitBtn.disabled = true;
                    this.$refs.attendanceForm.submit();
                }
            });
        }
    }
}
</script>
@endsection
