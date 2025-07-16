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
                'id' => 1,
                'user_id' => 1,
                'customer_id' => 1,
                'created_at' => '2025-07-16 14:14:02',
                'updated_at' => '2025-07-16 14:14:02',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'customer_id' => 2,
                'created_at' => '2025-07-16 14:14:24',
                'updated_at' => '2025-07-16 14:14:24',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'customer_id' => 3,
                'created_at' => '2025-07-16 14:14:41',
                'updated_at' => '2025-07-16 14:14:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}