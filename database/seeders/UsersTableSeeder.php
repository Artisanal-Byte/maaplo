<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                // 'id' => 1,
                'name' => 'Test User',
                'email' => 'test@gmail.com',
                'phone' => NULL,
                'password' => '$2y$12$zdLzNVoYvaDayhivejXnx.JXUg6zLIju2uhb.uaTkn5MnTVWkGx1y',
                'organization_name' => NULL,
                'address' => NULL,
                'organization_logo' => NULL,
                'avatar' => NULL,
                'hash_organization' => true,
                'thumbnail_logo' => NULL,
                'subscription_plan_id' => 1,
                'status' => true,
                'validity' => NULL,
                'email_verified_at' => '2025-06-12 09:52:14',
                'remember_token' => 'Ft8k05994h',
                'created_at' => '2025-06-12 09:52:14',
                'updated_at' => '2025-06-12 09:52:14',
                'deleted_at' => NULL,
                'organization_id' => NULL,
            ),
        ));


    }
}
