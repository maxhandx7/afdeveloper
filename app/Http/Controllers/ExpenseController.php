<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $expenses = Expense::get();
        return view('admin.expense.index', compact('expenses'));
    }


    public function create()
    {
        return view('admin.expense.create');
    }


    public function store(StoreExpenseRequest $request, Expense $expense)
    {
        try {
            $expense->my_store($request);
            return redirect()->route('expenses.index')->with('success', 'Gasto credado con éxito');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el gasto'.$th);
        }
    }

    public function show(Expense $expense)
    {
        return view('admin.expense.show', compact('expense'));
    }


    public function edit(Expense $expense)
    {
        return view('admin.expense.edit', compact('expense'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        try {
            $expense->my_update($request);
            return redirect()->route('expenses.index')->with('success', 'gasto modificado');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el gasto');
        }
    }


    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            return redirect()->route('expenses.index')->with('success', 'gasto eliminado');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el gasto');
        }
    }

    

}
