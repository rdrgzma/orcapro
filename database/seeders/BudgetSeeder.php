<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Support\Str;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        Budget::create([
            'company_id' => $user->company_id,
            'client_id' => 1,
            'subtotal' => 100.00,
            'total' => 100.00,
            'status' => 'sent',
            'token' => Str::random(32),
        ]);

        Budget::create([
            'company_id' => $user->company_id,
            'client_id' => 1,
            'subtotal' => 200.00,
            'total' => 200.00,
            'status' => 'approved',
            'token' => Str::random(32),
        ]);
    }
}
