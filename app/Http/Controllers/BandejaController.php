<?php

namespace App\Http\Controllers;

use App\Models\Bandeja;
use App\Http\Requests\StoreBandejaRequest;
use App\Http\Requests\UpdateBandejaRequest;

class BandejaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener todos los registros de la tabla 'bandejas'
        $bandejas = Bandeja::get();

        // Retornar la vista con los datos
        return view('admin.bandeja.index', compact('bandejas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBandejaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBandejaRequest $request)
    {
        Bandeja::create($request->validated());

        

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bandeja  $bandeja
     * @return \Illuminate\Http\Response
     */
    public function show(Bandeja $bandeja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bandeja  $bandeja
     * @return \Illuminate\Http\Response
     */
    public function edit(Bandeja $bandeja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBandejaRequest  $request
     * @param  \App\Models\Bandeja  $bandeja
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBandejaRequest $request, Bandeja $bandeja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bandeja  $bandeja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bandeja $bandeja)
    {
        //
    }
}
