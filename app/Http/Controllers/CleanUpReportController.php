<?php

namespace App\Http\Controllers;

use App\Models\CleanUpReport;
use App\Models\CleanUpTeam;
use App\Models\User;
use Illuminate\Http\Request;

class CleanUpReportController extends Controller
{
    public function index(Request $request)
    {
        $query = CleanUpReport::with(['team', 'assignedUser', 'reactions']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('location', 'like', '%' . $search . '%')
                    ->orWhere('concern_type', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('reporter_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reporter_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'concern_type' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:4096',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        CleanUpReport::create([
            'reporter_name' => $request->reporter_name,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'concern_type' => $request->concern_type,
            'description' => $request->description,
            'photo' => $photoPath,
            'status' => 'Pending',
        ]);

        return redirect('/')->with('success', 'Report submitted!');
    }

    public function show(CleanUpReport $report)
    {
        $report->load(['team', 'assignedUser', 'reactions', 'comments.user', 'comments.reactions']);
        return view('reports.show', compact('report'));
    }

    public function admin()
    {
        $reports = CleanUpReport::with(['team', 'assignedUser', 'comments', 'reactions'])->latest()->get();
        $teams = CleanUpTeam::orderBy('team_name')->get();
        $personnels = User::where('role', 'personnel')->orderBy('name')->get();

        $total = CleanUpReport::count();
        $pending = CleanUpReport::where('status', 'Pending')->count();
        $verified = CleanUpReport::where('status', 'Verified')->count();
        $progress = CleanUpReport::where('status', 'In Progress')->count();
        $completed = CleanUpReport::where('status', 'Completed')->count();

        return view('reports.admin', compact('reports', 'teams', 'personnels', 'total', 'pending', 'verified', 'progress', 'completed'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Verified,In Progress,Completed',
            'clean_up_team_id' => 'nullable|exists:clean_up_teams,id',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $report = CleanUpReport::findOrFail($id);
        $assignedUserId = $request->assigned_user_id;

        // Auto assign if admin leaves personnel blank.
        if (!$assignedUserId) {
            $personnelQuery = User::where('role', 'personnel');

            if ($request->filled('clean_up_team_id')) {
                $personnelQuery->where(function ($q) use ($request) {
                    $q->where('clean_up_team_id', $request->clean_up_team_id)
                      ->orWhereNull('clean_up_team_id');
                });
            }

            $personnel = $personnelQuery
                ->withCount(['assignedReports as active_reports_count' => function ($q) {
                    $q->whereIn('status', ['Pending', 'Verified', 'In Progress']);
                }])
                ->orderBy('active_reports_count')
                ->orderBy('name')
                ->first();

            $assignedUserId = $personnel?->id;
        }

        $report->update([
            'status' => $request->status,
            'clean_up_team_id' => $request->clean_up_team_id,
            'assigned_user_id' => $assignedUserId,
        ]);

        return back()->with('success', 'Report updated successfully!');
    }

    public function destroy($id)
    {
        CleanUpReport::findOrFail($id)->delete();
        return back()->with('success', 'Report deleted!');
    }
}
