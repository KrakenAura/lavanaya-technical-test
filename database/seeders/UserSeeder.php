<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Test Staff',
                'email' => 'staff@test.com',
                'role' => Role::STAFF,
            ],
            [
                'name' => 'Test Supervisor',
                'email' => 'supervisor@test.com',
                'role' => Role::SUPERVISOR,
            ],
            [
                'name' => 'Test Manager',
                'email' => 'manager@test.com',
                'role' => Role::MANAGER,
            ],
            [
                'name' => 'Test Director',
                'email' => 'director@test.com',
                'role' => Role::DIRECTOR,
            ],
            [
                'name' => 'Test Finance',
                'email' => 'finance@test.com',
                'role' => Role::FINANCE,
            ],
        ];


        foreach ($users as $data) {

            $role = Role::where(
                'name',
                $data['role']
            )->first();


            User::firstOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'name' => $data['name'],
                    'role_id' => $role->id,
                    'password' => Hash::make('P@ssw0rd123!'),
                ]
            );
        }
    }
}
