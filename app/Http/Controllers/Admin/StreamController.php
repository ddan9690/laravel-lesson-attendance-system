<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\User;
use App\Models\Grade;
use App\Models\Student;
use App\Models\FormStream;
use App\Models\GradeStream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StreamController
{
    public function showStreams($type, $id)
    {
        // Fetch all teachers except the system developer
        $teachers = User::where('email', '!=', 'dancanokeyo08@gmail.com')->get();

        if ($type === 'form') {
            $item = Form::with(['streams.classTeacher', 'curriculum'])->findOrFail($id);
            $streams = $item->streams;

            return view('admin.classes.showStreams', [
                'type' => 'form',
                'item' => $item,
                'streams' => $streams,
                'curriculum' => $item->curriculum,
                'teachers' => $teachers, // <-- pass teachers
            ]);
        }

        if ($type === 'grade') {
            $item = Grade::with(['streams.classTeacher', 'curriculum'])->findOrFail($id);
            $streams = $item->streams;

            return view('admin.classes.showStreams', [
                'type' => 'grade',
                'item' => $item,
                'streams' => $streams,
                'curriculum' => $item->curriculum,
                'teachers' => $teachers, // <-- pass teachers
            ]);
        }

        abort(404);
    }


    public function create($type, $id)
    {
        $teachers = User::where('email', '!=', 'dancanokeyo08@gmail.com')->get();

        if ($type === 'form') {
            $item = Form::findOrFail($id);
            $curriculumName = $item->curriculum->name;
        } elseif ($type === 'grade') {
            $item = Grade::findOrFail($id);
            $curriculumName = $item->curriculum->name;
        } else {
            abort(404, 'Invalid type');
        }

        return view('admin.classes.createStream', [
            'type' => $type,
            'item' => $item,
            'curriculumName' => $curriculumName,
            'teachers' => $teachers
        ]);
    }

    public function store(Request $request, $type, $id)
    {
        if ($type === 'form') {
            $request->validate([
                'name' => 'required|string|max:255',
                'class_teacher_id' => 'nullable|exists:users,id'
            ]);


            FormStream::create([
                'name' => $request->name,
                'form_id' => $id,
                'class_teacher_id' => $request->class_teacher_id,
            ]);
        } elseif ($type === 'grade') {
            $request->validate([
                'name' => 'required|string|max:255',
                'class_teacher_id' => 'nullable|exists:users,id'
            ]);



            GradeStream::create([
                'name' => $request->name,
                'grade_id' => $id,
                'class_teacher_id' => $request->class_teacher_id,
            ]);
        } else {
            abort(404, 'Invalid type');
        }

        return redirect()
            ->route('classes.streams.showStreams', ['type' => $type, 'id' => $id])
            ->with('success', 'Stream created successfully.');
    }

    public function edit($stream)
    {
        $stream = FormStream::find($stream) ?? GradeStream::findOrFail($stream);

        $type = $stream instanceof FormStream ? 'form' : 'grade';
        $item = $type === 'form' ? $stream->form : $stream->grade;
        $teachers = User::where('email', '!=', 'dancanokeyo08@gmail.com')->get();

        return view('admin.classes.editStream', [
            'stream' => $stream,
            'type' => $type,
            'item' => $item,
            'teachers' => $teachers
        ]);
    }

    public function update(Request $request, $stream)
    {
        $stream = FormStream::find($stream) ?? GradeStream::findOrFail($stream);

        $type = $stream instanceof FormStream ? 'form' : 'grade';

        $request->validate([
            'name' => 'required|string|max:255|unique:' .
                ($type === 'form' ? 'form_streams' : 'grade_streams') . ',name,' . $stream->id,
            'class_teacher_id' => 'nullable|exists:users,id'
        ]);

        $stream->update([
            'name' => $request->name,
            'class_teacher_id' => $request->class_teacher_id
        ]);

        $parentId = $type === 'form' ? $stream->form_id : $stream->grade_id;

        return redirect()
            ->route('classes.streams.showStreams', ['type' => $type, 'id' => $parentId])
            ->with('success', 'Stream updated successfully.');
    }

    public function destroy($stream)
    {
        $stream = FormStream::find($stream) ?? GradeStream::findOrFail($stream);

        $type = $stream instanceof FormStream ? 'form' : 'grade';
        $parentId = $type === 'form' ? $stream->form_id : $stream->grade_id;

        $stream->delete();

        return redirect()
            ->route('classes.streams.showStreams', ['type' => $type, 'id' => $parentId])
            ->with('success', 'Stream deleted successfully.');
    }



    public function manageStudents($streamId)
    {
        // CBC first, then 844
        $stream = GradeStream::find($streamId) ?? FormStream::findOrFail($streamId);

        // Detect type
        $type = $stream instanceof GradeStream ? 'grade' : 'form';

        // Parent class (Grade or Form)
        $item = $type === 'grade' ? $stream->grade : $stream->form;

        // Student fetch logic
        if ($type === 'grade') {
            $students = $stream->students()->orderBy('name')->get();
        } else {
            $students = collect();
        }

        return view('admin.classes.streamStudents', [
            'stream' => $stream,
            'students' => $students,
            'type' => $type,
            'item' => $item
        ]);
    }



    // Show form to add a student
    public function addStudentForm($streamId)
    {
        $stream = FormStream::find($streamId) ?? GradeStream::findOrFail($streamId);
        $type = $stream instanceof FormStream ? 'form' : 'grade';
        $item = $type === 'form' ? $stream->form : $stream->grade;

        return view('admin.classes.addStreamStudent', [
            'stream' => $stream,
            'type' => $type,
            'item' => $item
        ]);
    }

    // Store a new student
    public function storeStudent(Request $request, $streamId)
    {
        $stream = FormStream::find($streamId) ?? GradeStream::findOrFail($streamId);
        $type = $stream instanceof FormStream ? 'form' : 'grade';
        $item = $type === 'form' ? $stream->form : $stream->grade;

        $request->validate([
            'name' => 'required|string|max:255',
            'adm' => 'required|string|max:50|unique:students,adm'
        ]);

        $student = new Student([
            'name' => $request->name,
            'adm' => $request->adm,
            'grade_id' => $type === 'grade' ? $item->id : null,
            'form_id' => $type === 'form' ? $item->id : null,
            'stream_id' => $stream->id,
        ]);

        $stream->students()->save($student);

        return redirect()->route('classes.streams.students', $stream->id)
            ->with('success', 'Student added successfully.');
    }

    // Delete a student
    public function deleteStudent($studentId)
    {
        $student = Student::findOrFail($studentId);
        $streamId = $student->stream_id;
        $student->delete();

        return redirect()->route('classes.streams.students', $streamId)
            ->with('success', 'Student deleted successfully.');
    }

    public function updateTeacher(Request $request)
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'stream_id' => 'required',
        ]);


        $stream = FormStream::find($request->stream_id)
            ?? GradeStream::findOrFail($request->stream_id);

        $stream->class_teacher_id = $request->teacher_id;
        $stream->save();

        return response()->json([
            'success' => true,
            'message' => 'Class teacher updated successfully.'
        ]);
    }
}
