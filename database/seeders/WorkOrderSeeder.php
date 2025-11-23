<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkOrder;
use App\Models\User;

class WorkOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        WorkOrder::create([
            'company_id' => $user->company_id,
            'client_id' => 1,
            'budget_id' => 2,
            'status' => 'open',
        ]);

        WorkOrder::create([
            'company_id' => $user->company_id,
            'client_id' => 1,
            'status' => 'in_progress',
        ]);
    }
}
