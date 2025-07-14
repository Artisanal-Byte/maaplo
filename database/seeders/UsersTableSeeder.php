<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $users = ['sahil', 'riya', 'brijesh', 'richa', 'bhaviljain'];

        $data = [];

        foreach ($users as $name) {
            // Regular user
            $data[] = [
                'name' => ucfirst($name),
                'email' => $name . '@example.com',
                'phone' => null,
                'password' => Hash::make('test@123'), // default password
                'address' => null,
                'avatar' => null,
                'hash_organization' => true,
                'subscription_plan_id' => 1,
                'status' => true,
                'validity' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'organization_id' => null,
                'role' => 'user',
            ];

            // Admin user
            $data[] = [
                'name' => ucfirst($name) . 'Admin',
                'email' => $name . 'admin@example.com',
                'phone' => null,
                'password' => Hash::make('test@123'), // default admin password
                'address' => null,
                'avatar' => null,
                'hash_organization' => true,
                'subscription_plan_id' => 1,
                'status' => true,
                'validity' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
                'organization_id' => null,
                'role' => 'admin',
            ];
        }

        DB::table('users')->insert($data);
    }
}
