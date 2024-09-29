<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'email',
        'rol',
    ];


    public function my_store($request)
    {

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        } else {
            $imagePath = 'images/LOGO.png.png';
        }

        self::create([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
            'image' => $imagePath,
        ]);
    }

    public function my_update($request, $client)
    {
        
        $client = $this::findOrFail($client->id);

        if ($request->hasFile('image')) {
            if ($client->image && file_exists(public_path($client->image))) {
                unlink(public_path($client->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        } else {
            $imagePath = $client->image;
        }
        
        $this->update([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
            'image' => $imagePath,
        ]);
    }
}
