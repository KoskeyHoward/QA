<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Store public feedback submission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        Feedback::create($validated);

        return back()->with('success', 'Thank you for your feedback!');
    }

    // Admin dashboard view
    public function dashboard()
    {
        $pendingFeedback = Feedback::where('approved', false)->latest()->get();
        $approvedFeedback = Feedback::where('approved', true)->latest()->get();

        return view('dashboard', compact('pendingFeedback', 'approvedFeedback'));
    }

    // Approve feedback
    public function approve(Feedback $feedback)
    {
        $feedback->update(['approved' => true]);

        return back()->with('success', 'Feedback approved!');
    }

    // Delete feedback
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return back()->with('success', 'Feedback deleted!');
    }
}