<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BudgetSentMail;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = auth()->user()->budgets;
        return view('budgets.index', compact('budgets'));
    }

    public function show(Budget $budget)
    {
        $budget->load('items', 'client');
        return view('budgets.show', compact('budget'));
    }
    public function convertToWorkOrder(Budget $budget)
    {
        if ($budget->status !== 'approved') {
            return back()->with('error', 'Somente orçamentos aprovados podem ser convertidos.');
        }

        $workOrder = WorkOrder::create([
            'company_id' => $budget->company_id,
            'client_id'  => $budget->client_id,
            'budget_id'  => $budget->id,
            'status'     => 'open',
        ]);

        return redirect()->route('work-orders.show', $workOrder->id)
            ->with('success', 'Ordem de serviço gerada com sucesso!');
    }
    public function send(Budget $budget)
    {
        if(!$budget->client || !$budget->client->email)
            return back()->with('error', 'Cliente sem e-mail cadastrado!');

        Mail::to($budget->client->email)->send(new BudgetSentMail($budget));
        $budget->update(['status' => 'sent']);

        return back()->with('success', 'Orçamento enviado com sucesso!');
    }


}
