<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
    ];


    public function my_store($request)
    {
        if (!is_numeric($request->amount)) {
            $numericValue = str_replace(['$', ',', ' '], '', $request->amount);
            $request->amount = number_format((float) $numericValue, 2, '.', '');
        }
        self::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);
    }

    public function my_update($request)
    {
        if (!is_numeric($request->amount)) {
            $numericValue = str_replace(['$', ',', ' '], '', $request->amount);
            $request->amount = number_format((float) $numericValue, 2, '.', '');
        }
        $this->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);
    }
}
