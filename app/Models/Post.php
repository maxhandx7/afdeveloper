<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'image',
        'title',
        'long_description',
        'status',
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
            'title' => $request->title,
            'long_description' => $request->long_description,
            'image' => $imagePath,
        ]);
    }

    public function my_update($request, $link)
    {
        
        $link = $this::findOrFail($link->id);

        if ($request->hasFile('image')) {
            if ($link->image && file_exists(public_path($link->image))) {
                unlink(public_path($link->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
        } else {
            $imagePath = $link->image;
        }
        
        $this->update([
            'title' => $request->title,
            'long_description' => $request->long_description,
            'image' => $imagePath,
        ]);
    }
}
