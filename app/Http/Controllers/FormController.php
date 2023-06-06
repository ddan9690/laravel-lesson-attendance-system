<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::paginate(10); // Assuming you want to paginate the classes

        return view('remedial.pages.classes.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('remedial.pages.classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('forms')],
        ]);

        $form = new Form();
        $form->name = $request->input('name');
        $form->save();

        return redirect()->route('forms.index')->with('success', 'Form created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $form = Form::findOrFail($id);
        // Assuming you have a Form model and want to edit a specific form

        return view('remedial.pages.classes.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => ['required', Rule::unique('forms')->ignore($id)],
        ]);

        try {
            // Find the form by ID
            $form = Form::findOrFail($id);

            // Update the form with the validated data
            $form->update($validatedData);

            // Redirect to the index page with a success message
            return redirect()->route('forms.index')->with('success', 'Form updated successfully');
        } catch (\Exception $e) {
            // Redirect back with an error message if an exception occurs
            return redirect()->back()->with('error', 'Failed to update form. Please try again.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the record by its ID
            $form = Form::findOrFail($id);

            // Delete the record
            $form->delete();

            // Return a JSON response indicating success

            return redirect()->back()->with('success', 'Form deleted successfully');
        } catch (\Exception $e) {
            // Return a JSON response indicating failure

            return redirect()->back()->with('error', 'Failed to delete form. Please try again.');
        }
    }

    public function showAttendance($id)
    {
        $form = Form::findOrFail($id);
        $weeks = Week::all();

        return view('remedial.pages.attendances.showformsperweek', compact('form', 'weeks'));
    }

    public function showAttendanceWeek($id, $week_id)
{
    $form = Form::findOrFail($id);
    $week = Week::findOrFail($week_id);
    $attendances = $form->attendances->where('week_id', $week_id)->groupBy('lesson_id');

    return view('remedial.pages.attendances.showweekattendance', compact('form', 'week', 'attendances'));
}


}
