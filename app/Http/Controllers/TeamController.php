<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('teams');
    }

    public function index()
    {
        $teams = Team::get();

        return view('admin.team.index', compact('teams'));
    }


    public function create()
    {
        return view('admin.team.create');
    }


    public function store(StoreRequest $request, Team $team)
    {
        try {
            $team->my_store($request);
            return redirect()->route('teams.index')->with('success', 'equipo credado con éxito');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el equipo');
        }
    }

    public function show(Team $team)
    {
        return view('admin.team.show', compact('team'));
    }


    public function edit(Team $team)
    {
        return view('admin.team.edit', compact('team'));
    }

    public function update(UpdateRequest $request, Team $team)
    {
        try {
            $team->my_update($request, $team);
            return redirect()->route('teams.index')->with('success', 'Equipo modificado');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el equipo');
        }
    }


    public function destroy(Team $team)
    {
        try {
            $team->delete();
            return redirect()->route('teams.index')->with('success', 'Equipo eliminado');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el equipo');
        }
    }

    public function teams()
    {
        $teams =  Team::get();
        return view('teams', compact('teams'));
    }
}
