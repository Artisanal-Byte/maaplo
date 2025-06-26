<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersCustomersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users_customers')->delete();

        \DB::table('users_customers')->insert(array (
            0 =>
            array (
                // 'id' => 1,
                'user_id' => 1,
                'customer_id' => 1,
                'created_at' => '2025-06-12 10:49:52',
                'updated_at' => '2025-06-12 10:49:52',
                'deleted_at' => NULL,
            ),
        ));


    }
}
