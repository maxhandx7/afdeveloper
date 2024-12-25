<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Income;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $incomes = Income::get();
        return view('admin.income.index', compact('incomes'));
    }


    public function create()
    {
        return view('admin.income.create');
    }


    public function store(StoreIncomeRequest $request, Income $income)
    {
        try {
            $income->my_store($request);
            return redirect()->route('incomes.index')->with('success', 'Ingreso credado con éxito');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el ingreso'.$th);
        }
    }

    public function show(Income $income)
    {
        return view('admin.income.show', compact('income'));
    }


    public function edit(Income $income)
    {
        return view('admin.income.edit', compact('income'));
    }

    public function update(UpdateIncomeRequest $request, Income $income)
    {
        try {
            $income->my_update($request);
            return redirect()->route('incomes.index')->with('success', 'Ingreso modificada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el ingreso');
        }
    }


    public function destroy(Income $income)
    {
        try {
            $income->delete();
            return redirect()->route('incomes.index')->with('success', 'Ingreso eliminada');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el ingreso');
        }
    }

    

}
