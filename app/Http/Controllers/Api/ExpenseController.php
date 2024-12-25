<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return response()->json(Expense::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $expense = Expense::create($request->all());
        return response()->json($expense, 201);
    }

    public function show($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Fallo al mostrar el gasto'], 404);
        }
        return response()->json($expense, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric',
            'date' => 'sometimes|date',
        ]);

        
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Fallo al actualizar gasto'], 404);
        }
        $expense->update($request->all());
        return response()->json($expense, 200);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'fallo al eliminar gasto'], 404);
        }
        $expense->delete();
        return response()->json(['message' => 'Gasto eliminado'], 200);
    }

    public function summary()
{
    $totalExpenses = Expense::sum('amount');
    $totalIncomes = Income::sum('amount');
    $balance = $totalIncomes - $totalExpenses;

    return response()->json([
        'total_expenses' => $totalExpenses,
        'total_incomes' => $totalIncomes,
        'balance' => $balance
    ], 200);
}
}
