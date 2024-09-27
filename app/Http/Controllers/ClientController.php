<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('clients');
    }

    public function index()
    {
        $clients = Client::get();

        return view('admin.client.index', compact('clients'));
    }


    public function create()
    {
        return view('admin.client.create');
    }


    public function store(StoreRequest $request, Client $client)
    {
        try {
            $client->my_store($request);
            return redirect()->route('clients.index')->with('success', 'cliente credado con éxito');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el cliente');
        }
    }

    public function show(Client $client)
    {
        return view('admin.client.show', compact('client'));
    }


    public function edit(Client $client)
    {
        return view('admin.client.edit', compact('client'));
    }

    public function update(UpdateRequest $request, Client $client)
    {
        try {
            $client->my_update($request, $client);
            return redirect()->route('clients.index')->with('success', 'Cliente modificado');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la cliente');
        }
    }


    public function destroy(Client $client)
    {
        try {
            $client->delete();
            return redirect()->route('clients.index')->with('success', 'Cliente eliminada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar la cliente');
        }
    }

    public function clients()
    {
        $clients =  Client::get();
        return view('clients', compact('clients'));
    }

}
