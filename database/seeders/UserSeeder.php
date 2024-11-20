<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => 'password'
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@company.com',
                'password' => 'password'
            ],
            [
                'name' => 'User',
                'email' => 'user@company.com',
                'password' => 'password'
            ]
        ];

        foreach ($accounts as $account) {
            User::create([
                'name' => $account['name'],
                'email' => $account['email'],
                'password' => Hash::make($account['password'])
            ]);
        }

    }
}
