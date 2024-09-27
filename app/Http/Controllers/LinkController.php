<?php

namespace App\Http\Controllers;

use App\Http\Requests\Link\StoreRequest;
use App\Http\Requests\Link\UpdateRequest;
use App\Models\Link;


class LinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('proyects');
    }

    public function index()
    {
        $links = Link::get();

        return view('admin.link.index', compact('links'));
    }


    public function create()
    {
        return view('admin.link.create');
    }


    public function store(StoreRequest $request, Link $link)
    {
        try {
            $link->my_store($request);
            return redirect()->route('links.index')->with('success', 'Enlace credada con éxito');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear la enlace');
        }
    }

    public function show(Link $link)
    {
        return view('admin.link.show', compact('link'));
    }


    public function edit(Link $link)
    {
        return view('admin.link.edit', compact('link'));
    }

    public function update(UpdateRequest $request, Link $link)
    {
        try {
            $link->my_update($request, $link);
            return redirect()->route('links.index')->with('success', 'Enlace modificada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la enlace');
        }
    }


    public function destroy(Link $link)
    {
        try {
            $link->delete();
            return redirect()->route('links.index')->with('success', 'Enlace eliminada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar la enlace');
        }
    }

    public function proyects()
    {
        $proyects =  Link::where('status', 'ACTIVE')->get();
        return view('proyects', compact('proyects'));
    }

    public function change_status(Link $link)
    {
        if ($link->status == 'ACTIVE') {
            $link->update(['status' => 'DESACTIVATED']);
            return redirect()->back()->with('info', 'Enlace inactivado');
        } else {
            $link->update(['status' => 'ACTIVE']);
            return redirect()->back()->with('info', 'Enlace activado');
        }
    }
}
    