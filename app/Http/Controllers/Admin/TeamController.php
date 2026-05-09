<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleanUpTeam;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = CleanUpTeam::withCount('reports')->latest()->get();

        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'assigned_area' => 'nullable|string|max:255',
        ]);

        CleanUpTeam::create($request->only([
            'team_name',
            'contact_number',
            'assigned_area',
        ]));

        return redirect('/admin/teams')->with('success', 'Team created successfully.');
    }

    public function edit(CleanUpTeam $team)
    {
        $personnels = User::where('role', 'personnel')->get();

        return view('admin.teams.edit', compact('team', 'personnels'));
    }

    public function update(Request $request, CleanUpTeam $team)
    {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'assigned_area' => 'nullable|string|max:255',
        ]);

        $team->update([
            'team_name' => $request->team_name,
            'contact_number' => $request->contact_number,
            'assigned_area' => $request->assigned_area,
        ]);

        User::where('clean_up_team_id', $team->id)
            ->update([
                'clean_up_team_id' => null
            ]);

        if ($request->personnel_ids) {
            User::whereIn('id', $request->personnel_ids)
                ->update([
                    'clean_up_team_id' => $team->id
                ]);
        }

        return redirect('/admin/teams')
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(CleanUpTeam $team)
    {
        $team->delete();

        return redirect('/admin/teams')->with('success', 'Team deleted successfully.');
    }
}