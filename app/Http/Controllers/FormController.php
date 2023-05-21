<?php

namespace App\Http\Controllers;

use App\Models\Form;
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

    return response()->json([
        'success' => true,
        'message' => 'Form created successfully.',
        'form' => $form, // Include the saved form entry if needed
    ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
{
    // Find the record by its ID
    // $form = Form::findOrFail($id);

    // Delete the record
    $form->delete();

    // Return a JSON response indicating success
    return response()->json(['success' => true, 'message' => 'Form deleted successfully']);
}
}
