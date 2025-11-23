<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Adimistrador',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
        ]);
        User::create(
            [
                'name' => 'User Teste',
                'email' => 'user@user.com',
                'password' => 'user123',

            ]
        );
    }
}

