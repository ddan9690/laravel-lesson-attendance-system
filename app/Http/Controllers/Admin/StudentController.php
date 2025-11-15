<?php

namespace App\Http\Controllers\Admin;

use App\Models\Term;
use App\Models\Student;
use App\Models\GradeStream;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StudentController
{
    public function create($streamId)
    {
        $stream = GradeStream::findOrFail($streamId);
        $academicYears = AcademicYear::with('terms')->get();

        return view('admin.students.create', [
            'stream' => $stream,
            'academicYears' => $academicYears,
        ]);
    }

    public function getTermsByYear($yearId)
    {
        $terms = Term::where('academic_year_id', $yearId)->get();

        return response()->json($terms);
    }

    public function store(Request $request, $streamId)
    {
        // Find the stream
        $stream = GradeStream::findOrFail($streamId);

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'adm' => 'required|integer|unique:students,adm',
            'phone' => 'nullable|string|max:20',
            'joined_academic_year_id' => 'required|exists:academic_years,id',
            'joined_term_id' => 'required|exists:terms,id',
        ]);

        // Get Academic Year and Term
        $academicYear = AcademicYear::with('terms')->findOrFail($validated['joined_academic_year_id']);
        $term = $academicYear->terms->firstWhere('id', $validated['joined_term_id']);

        if (!$term) {
            abort(404, 'Selected term not found for the academic year.');
        }

        // Create the student
        Student::create([
            'name' => $validated['name'],
            'adm' => $validated['adm'],
            'phone' => $validated['phone'] ?? null,
            'grade_id' => $stream->grade_id,
            'grade_stream_id' => $stream->id,
            'joined_academic_year_id' => $academicYear->id,
            'joined_term_id' => $term->id,
            'added_by' => auth()->id(),
            // Status defaults to 'active'
        ]);

        // Redirect back with success
        return redirect()
            ->route('classes.streams.students', $stream->id)
            ->with('success', 'Student added successfully.');
    }


    public function edit($studentId)
    {
        $student = Student::findOrFail($studentId);
        $academicYears = AcademicYear::with('terms')->get();

        return view('admin.students.edit', [
            'student' => $student,
            'academicYears' => $academicYears,
        ]);
    }

    public function update(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'adm' => 'required|integer|unique:students,adm,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'joined_type' => 'required|in:current,specify',
            'joined_academic_year_id' => 'required_if:joined_type,specify|exists:academic_years,id',
            'joined_term_id' => 'required_if:joined_type,specify|exists:terms,id',
        ]);

        // Determine Academic Year and Term
        if ($validated['joined_type'] === 'current') {
            $academicYear = AcademicYear::with('terms')->where('is_active', true)->firstOrFail();
            $term = $academicYear->terms->firstWhere('is_active', true);
            if (!$term) abort(404, 'No active term found for the current academic year.');
        } else {
            $academicYear = AcademicYear::with('terms')->findOrFail($validated['joined_academic_year_id']);
            $term = $academicYear->terms->firstWhere('id', $validated['joined_term_id']);
            if (!$term) abort(404, 'Selected term not found for the academic year.');
        }

        // Update student
        $student->update([
            'name' => $validated['name'],
            'adm' => $validated['adm'],
            'phone' => $validated['phone'] ?? null,
            'joined_academic_year_id' => $academicYear->id,
            'joined_term_id' => $term->id,
        ]);

        return redirect()
            ->route('classes.streams.students', $student->grade_stream_id)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy($studentId)
    {
        $student = Student::findOrFail($studentId);
        $streamId = $student->grade_stream_id;
        $student->delete();

        return redirect()
            ->route('classes.streams.students', $streamId)
            ->with('success', 'Student deleted successfully.');
    }

    public function importForm($streamId)
    {
        $stream = GradeStream::findOrFail($streamId);

        // Load academic years with their terms
        $academicYears = AcademicYear::with('terms')->get();

        return view('admin.students.import', [
            'stream' => $stream,
            'academicYears' => $academicYears,
        ]);
    }

    public function importStore(Request $request, $streamId)
    {
        $stream = GradeStream::findOrFail($streamId);

        $validated = $request->validate([
            'students_file' => 'required|file|mimes:xlsx,xls,csv',
            'joined_term_id' => 'required|exists:terms,id',
        ]);

        $term = Term::findOrFail($validated['joined_term_id']);
        $academicYearId = $term->academic_year_id;

        $import = new StudentsImport(
            $stream->id,
            $academicYearId,
            $term->id,
            Auth::id()
        );

        Excel::import($import, $request->file('students_file'));

        return redirect()
            ->route('classes.streams.students', $stream->id)
            ->with('success', 'Students imported successfully.');
    }
}
