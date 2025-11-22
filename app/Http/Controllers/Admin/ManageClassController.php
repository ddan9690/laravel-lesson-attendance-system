<?php

namespace App\Http\Controllers\Admin;

use App\Models\Form;
use App\Models\User;
use App\Models\Grade;
use App\Models\Curriculum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageClassController
{
    /**
     * Display a listing of curricula for managing classes.
     */
    public function index()
    {
        $curricula = Curriculum::all();
        return view('admin.classes.index', compact('curricula'));
    }



    public function show(Curriculum $curriculum)
    {
        // Determine type based on curriculum
        if (strtolower($curriculum->name) === '8-4-4') {
            $type = 'forms';
            $forms = $curriculum->forms()->with('supervisor')->get();
            $grades = collect(); // empty collection
        } elseif (strtolower($curriculum->name) === 'cbc') {
            $type = 'grades';
            $grades = $curriculum->grades()->with('supervisor')->get();
            $forms = collect(); // empty collection
        } else {
            $type = null;
            $forms = collect();
            $grades = collect();
        }

        // Get all teachers except the system developer
        $teachers = User::where('email', '!=', 'dancanokeyo08@gmail.com')->get();

        return view('admin.classes.show', compact('curriculum', 'type', 'forms', 'grades', 'teachers'));
    }

    
    public function updateSupervisor(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'nullable|exists:users,id',
            'form_id' => 'nullable|exists:forms,id',
            'grade_id' => 'nullable|exists:grades,id',
        ]);

        $supervisorId = $request->input('supervisor_id');

        if ($request->filled('form_id')) {
            $form = Form::findOrFail($request->input('form_id'));
            $form->class_supervisor_id = $supervisorId;
            $form->save();
        } elseif ($request->filled('grade_id')) {
            $grade = Grade::findOrFail($request->input('grade_id'));
            $grade->class_supervisor_id = $supervisorId;
            $grade->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No form or grade selected.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Supervisor updated successfully.'
        ]);
    }
}
