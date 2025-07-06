<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    // READ: Show approved feedback
    public function index()
    {
        return Feedback::where('approved', true)->get();
    }

    // CREATE: Store new feedback
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        return Feedback::create($validated);
    }

    // UPDATE: Approve a feedback
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->approved = $request->input('approved', true);
        $feedback->save();

        return response()->json(['message' => 'Feedback approved']);
    }

    // DELETE: Remove a feedback
    public function destroy($id)
    {
        Feedback::destroy($id);
        return response()->json(['message' => 'Feedback deleted']);
    }
    // New method for admin to get all unapproved feedback
    public function unapproved()
    {
    return Feedback::where('approved', false)->get();
    }

    // New method for admin to get all feedback
    public function all()
    {
        return Feedback::all();
    }
}
