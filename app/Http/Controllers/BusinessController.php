<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Http\Requests\Business\UpdateRequest;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $business = Business::get();
        $business = Business::where('id', 1)->firstOrFail();
        return view('admin.business.index', compact('business'));
    }
    public function update(UpdateRequest $request, Business $business)
    {
        try {
            $data = $request->except('_token', '_method', 'thead', 'facebook', 'twitter', 'instagram', 'show_letter', 'picture', 'linkedin');

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $image_name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path("/image"), $image_name);
                $data['logo'] = $image_name;
            }

            // Asigna valores a configuraciones
            $configurations = $business->configurations ?? [];
            if (!is_array($configurations)) {
                $configurations = [];
            }
            $configurations['show_letter'] = $request->input('show_letter', null);
            $configurations['thead'] = $request->input('thead', null);
            $configurations['facebook'] = $request->input('facebook', null);
            $configurations['twitter'] = $request->input('twitter', null);
            $configurations['instagram'] = $request->input('instagram', null);
            $configurations['linkedin'] = $request->input('linkedin', null);
            
            $business->configurations = $configurations;
    
            $business->update($data);

            return redirect()->route('business.index')->with('success', 'Se ha actualizado la empresa');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar la empresa: ' . $th->getMessage());
        }
    }


}
