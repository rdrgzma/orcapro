<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetPublicController extends Controller
{
    public function show($token)
    {
        $budget = Budget::where('token', $token)->with('items')->firstOrFail();
        return view('public.budget-show', compact('budget'));
    }

    public function approve($token)
    {
        $budget = Budget::where('token', $token)->firstOrFail();
        $budget->update(['status' => 'approved']);

        return back()->with('success','Orçamento aprovado com sucesso!');
    }

    public function reject($token)
    {
        $budget = Budget::where('token', $token)->firstOrFail();
        $budget->update(['status' => 'rejected']);

        return back()->with('error','Orçamento recusado!');
    }

    public function pdf($token)
    {
        $budget = Budget::where('token', $token)->with('items')->firstOrFail();
        $pdf = \PDF::loadView('pdf.budget', compact('budget'));
        return $pdf->download('orcamento-'.$budget->id.'.pdf');
    }
}
