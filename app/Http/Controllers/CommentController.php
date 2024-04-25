<?php

namespace App\Http\Controllers;

use App\Models\Week;

use App\Models\Comment;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;



class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $weeks = Week::orderBy('week_number', 'asc')->get();
    $selectedWeek = $request->week ?? $weeks->first()->id;

    $comments = Comment::where('week_id', $selectedWeek)
        ->orderBy('created_at', 'desc')
        ->with('user')
        ->paginate(10);

    return view('remedial.pages.comments.index', compact('weeks', 'comments', 'selectedWeek'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $weeks = Week::all();
        return view('remedial.pages.comments.create', compact('weeks'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'week_id' => 'required|exists:weeks,id',
            'comment' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->week_id = $validatedData['week_id'];
        $comment->comment = $validatedData['comment'];
        $comment->save();

        // Return simple JSON response
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
        ]);
    }


    /**
     * Display the specified resource.
     */
    // public function show(Comment $comment)
    // {
    //     //
    // }

    public function edit($id)
    {
        $comment = Comment::find($id);
        $weeks = Week::all();
        return view('remedial.pages.comments.edit', compact('comment', 'weeks'));
    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'week_id' => 'required|exists:weeks,id',
        'comment' => 'required|string|max:255',
    ]);

    $comment = Comment::find($id);
    $comment->week_id = $validatedData['week_id'];
    $comment->comment = $validatedData['comment'];
    $comment->save();

    return redirect()->route('comment.index')->with('success', 'Comment updated successfully.');
}


public function destroy($id)
{
    $comment = Comment::find($id);
    $comment->delete();

    return redirect()->route('comment.index')->with('success', 'Comment deleted successfully.');
}

public function exportToPDF()
    {
        // Get the comments, including the related week, and order them by week_number
        $comments = Comment::with('week')->orderBy('week_id', 'asc')->get();


        // Group the comments by week_number using Laravel collection's groupBy method
        $groupedComments = $comments->groupBy('week.week_number');

        // Pass the grouped comments to the view
        $data = [
            'groupedComments' => $groupedComments,
        ];

        // Load the view using the Blade file
        $pdf = PDF::loadView('remedial.pages.comments.pdfexport', $data);

        // Return the PDF as a response for streaming
        return $pdf->stream('remedial_notices.pdf');
    }

    public function deleteAll()
    {

        Comment::truncate();


        return redirect()->back()->with('success', 'All comments have been deleted successfully.');
    }





}
