<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Curriculum;
use App\Models\LearningArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectLearningAreaController
{
    public function index()
    {

        $curricula = Curriculum::with([
            'subjects',
            'learningAreas'
        ])->get();



        return view('admin.subjects.index', compact('curricula'));
    }

    public function create(Curriculum $curriculum, Request $request)
    {

        $type = $request->query('type', 'subject');
        if (!in_array($type, ['subject', 'learning_area'])) {
            abort(400, 'Invalid type.');
        }
        return view('admin.subjects.create', compact('curriculum', 'type'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'curriculum_id' => 'required|exists:curricula,id',
            'name' => 'required|string|max:255',
            'short' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:subject,learning_area',
        ]);

        $data = $request->only(['name', 'short', 'code', 'curriculum_id']);

        if ($request->type === 'subject') {
            Subject::create($data);
        } else {
            LearningArea::create($data);
        }

        return redirect()->route('subjects.index')
            ->with('success', ucfirst($request->type) . ' added successfully!');
    }

    public function edit($id)
    {
        // Find the subject or learning area
        $item = Subject::find($id) ?? LearningArea::find($id);

        if (!$item) {
            return redirect()->route('subjects.index')
                ->with('error', 'Item not found.');
        }

        // Determine type
        $type = $item instanceof Subject ? 'subject' : 'learning_area';
        $curriculum = $item->curriculum;

        return view('admin.subjects.edit', compact('item', 'type', 'curriculum'));
    }

    // Update item
    public function update(Request $request, $id)
    {
        $item = Subject::find($id) ?? LearningArea::find($id);

        if (!$item) {
            return redirect()->route('subjects.index')
                ->with('error', 'Item not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'short' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50',
        ]);

        $item->update($request->only(['name', 'short', 'code']));

        return redirect()->route('subjects.index')
            ->with('success', ucfirst($request->type) . ' updated successfully!');
    }

    // Delete item
    public function destroy($id)
    {
        $item = Subject::find($id) ?? LearningArea::find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found.'], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!'
        ]);
    }
}
