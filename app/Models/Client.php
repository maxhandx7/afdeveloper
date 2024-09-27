<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;


    protected $fillable = [
        'image',
        'name',
        'description',
    ];


    public function my_store($request)
    {

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        } else {
            $imagePath = 'images/lsoft_mini.png';
        }

        self::create([
            'name' => $request->name,
            'description' => $request->description,
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
            'description' => $request->description,
            'image' => $imagePath,
        ]);
    }
}
