<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WeeksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weeks = Week::paginate(10); // Assuming you want to paginate the classes

        return view('remedial.pages.weeks.index', compact('weeks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('remedial.pages.weeks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'week_number' => [
                'required',
                Rule::unique('weeks')->where(function ($query) use ($request) {
                    return $query->where('week_number', $request->week_number);
                }),
            ],
        ], [
            'week_number.unique' => 'Week ' . $request->week_number . ' already exists.',
        ]);

        $week = new Week();
        $week->week_number = $request->week_number;
        $week->from = Carbon::now()->toDateString();
        $week->to = Carbon::now()->toDateString();
        $week->save();

        return redirect()->route('weeks.index')->with('success', 'Week created successfully.');
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
    public function destroy(Week $week)
{
    try {
        // Delete the week
        $week->delete();

        return redirect()->route('weeks.index')->with('success', 'Week deleted successfully');
    } catch (\Exception $e) {
        return redirect()->route('weeks.index')->with('error', 'Failed to delete week');
    }
}

}
