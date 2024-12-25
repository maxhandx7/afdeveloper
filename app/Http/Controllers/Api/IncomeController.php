<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        return response()->json(Income::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $income = Income::create($request->all());
        return response()->json($income, 201);
    }

    public function show($id)
    {
        $income = Income::find($id);
        if (!$income) {
            return response()->json(['message' => 'Fallo en los ingresos'], 404);
        }
        return response()->json($income, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
        ]);

        $income = Income::find($id);
        if (!$income) {
            return response()->json(['message' => 'Fallo en los ingresos'], 404);
        }
        $income->update($request->all());
        return response()->json($income, 200);
    }

    public function destroy($id)
    {
        $income = Income::find($id);
        if (!$income) {
            return response()->json(['message' => 'Fallo al eliminar ingresois'], 404);
        }
        $income->delete();
        return response()->json(['message' => 'Ingreso eliminado'], 200);
    }

   
}
