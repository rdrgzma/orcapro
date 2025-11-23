<x-layouts.app :title="__('Work Orders')">
    <div class="p-6">
        <div class="bg-white shadow rounded">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-bold">Work Orders</h2>
                <a href="{{ route('work-orders.create') }}" class="btn bg-blue-600 text-white p-2 rounded">Create Work Order</a>
            </div>
            <div class="p-4">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Number</th>
                            <th class="text-left">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workOrders as $workOrder)
                            <tr>
                                <td>{{ $workOrder->number }}</td>
                                <td>{{ $workOrder->status }}</td>
                                <td class="text-right">
                                    <a href="{{ route('work-orders.show', $workOrder) }}" class="text-blue-600">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
