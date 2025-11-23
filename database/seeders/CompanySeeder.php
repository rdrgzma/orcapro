<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        if ($user) {
            Company::create([
                'user_id' => $user->id,
                'name' => 'Default Company',
                'fantasy_name' => 'Default Fantasy Name',
                'document' => '00.000.000/0001-00',
            ]);
        }
    }
}
