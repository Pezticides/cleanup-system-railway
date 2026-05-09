<?php

namespace App\Http\Controllers;

use App\Models\CleanUpReport;
use App\Models\CleanUpTeam;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ReportActivity;
use App\Notifications\ReportAssignedNotification;

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
        $report->load([
            'team',
            'assignedUser',
            'reactions',
            'comments.user',
            'comments.reactions',
            'activities.user'
        ]);
        return view('reports.show', compact('report'));
    }

   public function admin()
    {
        $reports = CleanUpReport::with([
            'team',
            'assignedUser',
            'comments',
            'reactions'
        ])->latest()->get();

        $teams = CleanUpTeam::orderBy('team_name')->get();

        $personnels = User::where('role', 'personnel')
            ->orderBy('name')
            ->get();

        // Status Counts
        $total = CleanUpReport::count();

        $pending = CleanUpReport::where('status', 'Pending')->count();

        $verified = CleanUpReport::where('status', 'Verified')->count();

        $progress = CleanUpReport::where('status', 'In Progress')->count();

        $completed = CleanUpReport::where('status', 'Completed')->count();

        // Concern Type Analytics
        $concernLabels = CleanUpReport::select('concern_type')
            ->distinct()
            ->pluck('concern_type');

        $concernData = [];

        foreach ($concernLabels as $label) {

            $concernData[] = CleanUpReport::where(
                'concern_type',
                $label
            )->count();
        }

        // Monthly Reports Analytics
        $monthlyLabels = [];
        $monthlyData = [];

        for ($i = 5; $i >= 0; $i--) {

            $month = now()->subMonths($i);

            $monthlyLabels[] = $month->format('M');

            $monthlyData[] = CleanUpReport::whereYear(
                'created_at',
                $month->year
            )
            ->whereMonth(
                'created_at',
                $month->month
            )
            ->count();
        }

        return view('reports.admin', compact(
            'reports',
            'teams',
            'personnels',
            'total',
            'pending',
            'verified',
            'progress',
            'completed',
            'concernLabels',
            'concernData',
            'monthlyLabels',
            'monthlyData'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Verified,In Progress,Completed',

            'priority' => 'required|in:Low,Medium,High,Urgent',

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

        $oldStatus = $report->status;

        $oldPriority = $report->priority;

        $oldTeam = $report->clean_up_team_id;

        $oldPersonnel = $report->assigned_user_id;

        $report->update([

            'status' => $request->status,

            'priority' => $request->priority,

            'clean_up_team_id' => $request->clean_up_team_id,

            'assigned_user_id' => $assignedUserId,

        ]);

        // STATUS LOG
        if ($oldStatus != $request->status) {

            ReportActivity::create([

                'clean_up_report_id' => $report->id,

                'user_id' => auth()->id(),

                'action' => 'Status Updated',

                'description' =>
                    'Changed status from '
                    . $oldStatus .
                    ' to '
                    . $request->status,

            ]);
        }

        // PRIORITY LOG
        if ($oldPriority != $request->priority) {

            ReportActivity::create([

                'clean_up_report_id' => $report->id,

                'user_id' => auth()->id(),

                'action' => 'Priority Updated',

                'description' =>
                    'Changed priority from '
                    . $oldPriority .
                    ' to '
                    . $request->priority,

            ]);
        }

        // TEAM LOG
        if ($oldTeam != $request->clean_up_team_id) {

            $teamName = $report->team?->team_name ?? 'No Team';

            ReportActivity::create([

                'clean_up_report_id' => $report->id,

                'user_id' => auth()->id(),

                'action' => 'Team Assigned',

                'description' =>
                    'Assigned report to '
                    . $teamName,

            ]);
        }

        // PERSONNEL LOG
        if ($oldPersonnel != $assignedUserId) {

            $personnelName =
                $report->assignedUser?->name ?? 'No Personnel';

            if ($report->assignedUser) {

                $report->assignedUser->notify(

                    new ReportAssignedNotification($report)

                );
            }

            ReportActivity::create([

                'clean_up_report_id' => $report->id,

                'user_id' => auth()->id(),

                'action' => 'Personnel Assigned',

                'description' =>
                    'Assigned personnel: '
                    . $personnelName,

            ]);
        }

        return back()->with('success', 'Report updated successfully!');
    }

    public function destroy($id)
    {
        CleanUpReport::findOrFail($id)->delete();
        return back()->with('success', 'Report deleted!');
    }
}
