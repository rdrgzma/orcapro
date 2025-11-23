<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
public function run(){
    $user = User::first();
    $company = Company::first();
    $user->company_id = $company->id;
    $user->save();


    if ($user) {
    Client::create([
        'company_id' => $user->company_id,
        'name' => 'Default Client',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'street' => 'Default Street',
        'city' => 'Default City',
        'state' => 'Default State',
        'zip' => '12345-678'
        ]);

    }
}
}
