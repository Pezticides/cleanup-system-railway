<?php

namespace App\Http\Controllers;

use App\Models\CleanUpReport;
use App\Models\ReportComment;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function report(Request $request, CleanUpReport $report)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        Reaction::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'reactable_id' => $report->id,
                'reactable_type' => CleanUpReport::class,
            ],
            [
                'emoji' => $request->emoji,
            ]
        );

        return back();
    }

    public function comment(Request $request, ReportComment $comment)
    {
        $request->validate([
            'emoji' => 'required|string|max:10',
        ]);

        Reaction::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'reactable_id' => $comment->id,
                'reactable_type' => ReportComment::class,
            ],
            [
                'emoji' => $request->emoji,
            ]
        );

        return back();
    }
}