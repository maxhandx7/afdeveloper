<?php

namespace App\Http\Controllers;

use App\Models\Bandeja;
use App\Http\Requests\StoreBandejaRequest;
use App\Http\Requests\UpdateBandejaRequest;
use App\Mail\MensajeMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class BandejaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Carbon::setLocale('es');
        
        
        $bandejas = Bandeja::get();
        foreach ($bandejas as $badeja) {
            $badeja->fecha_formateada = Carbon::createFromFormat('Y-m-d H:i:s', $badeja->created_at)->isoFormat('D [de] MMMM [de] YYYY, h:mm A');
        }
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
        $this->enviarCorreo(
            $request->name,
            $request->email,
            $request->phone,
            $request->message
        );
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
        try {
            $bandeja->delete();
            return redirect()->route('bandeja.index')->with('success', 'mensaje eliminado');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el mensaje');
        }
    }


    public function enviarCorreo($nombre, $correo, $telefono, $mensaje)
    {

        Mail::to("Alancarabali@gmail.com")->send(new MensajeMail($nombre, $correo, $telefono, $mensaje));

        return "Correo enviado correctamente.";
    }
}
