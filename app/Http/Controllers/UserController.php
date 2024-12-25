<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users = User::get();
        return view('admin.user.index', compact('users'));
    }
    public function create()
    {
        return view('admin.user.create');
    }
    public function store(Request $request)
    {
        try {
            $user = new User();
            $user->my_store($request);
            return redirect()->route('users.index')->with('success', 'Nuevo usuario creado con éxito');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario');
        }
    }
    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        try {
            if ($user->id == 1) {
                return redirect()->route('users.index');
            } else {
                $user->my_update($request);
                return redirect()->route('users.index')->with('success', 'Usuario modificado');
            }
        } catch (\Throwable $th) {
            return redirect()->route('users.index')->with('error', 'Ocurrió un error al modificar el usuario');
        }
    }
    public function destroy(User $user)
    {
        try {
            if ($user->id == 1) {
                return back()->with('error', 'No se puede eliminar este usuario');
            } else {
                $user->delete();
                return back()->with('success', 'Usuario eliminado');
            }
        } catch (\Throwable $th) {
            return back()->with('error', 'Ocurrió un error al eliminar el usuario');
        }
    }
}
