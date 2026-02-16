<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalExpenses = Expense::sum('amount');
        $totalIncomes = Income::sum('amount');
        $balance = $totalIncomes - $totalExpenses;
        $expense = Expense::latest('updated_at')->first();
        $ExpenselastUpdated = $expense ? $expense->updated_at->format('H:i:s') : 'Sin registros';

        $income = Income::latest('updated_at')->first();
        $IncomelastUpdated = $income ? $income->updated_at->format('H:i:s') : 'Sin registros';

        return view('home', compact('totalExpenses', 'totalIncomes', 'balance', 'ExpenselastUpdated', 'IncomelastUpdated'));
    }


}
