<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = auth()->user()->workOrders;
        return view('work-orders.index', compact('workOrders'));
    }

    public function create()
    {
        $clients = auth()->user()->company->clients;
        return view('work-orders.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:open,in_progress,completed,delivered,canceled',
        ]);

        $workOrder = auth()->user()->company->workOrders()->create($validated);

        return redirect()->route('work-orders.show', $workOrder);
    }

    public function show(WorkOrder $workOrder)
    {
        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $clients = auth()->user()->company->clients;
        return view('work-orders.edit', compact('workOrder', 'clients'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:open,in_progress,completed,delivered,canceled',
        ]);

        $workOrder->update($validated);

        return redirect()->route('work-orders.show', $workOrder);
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('work-orders.index');
    }
}
