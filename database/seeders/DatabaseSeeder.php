<?php

namespace Database\Seeders;


use Database\Seeders\UserSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\CompanySeeder;
use Database\Seeders\BudgetSeeder;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            ClientSeeder::class,
            BudgetSeeder::class,
            WorkOrderSeeder::class,
        ]);
    }
}
