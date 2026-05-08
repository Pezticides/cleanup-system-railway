<?php

namespace App\Http\Controllers;

use App\Models\CleanUpReport;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function store(Request $request, CleanUpReport $report)
    {
        $request->validate([
            'body' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:4096',
        ]);

        if (!$request->filled('body') && !$request->hasFile('photo')) {
            return back()->withErrors(['body' => 'Write a comment or upload a photo.']);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('comment_photos', 'public');
        }

        $report->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'photo' => $photoPath,
        ]);

        return back()->with('success', 'Comment posted.');
    }
}
