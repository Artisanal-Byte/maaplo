<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customers')->delete();
        
        \DB::table('customers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'name' => 'testcustomer1',
                'gender' => 'f',
                'country_code' => '+91',
                'phone' => '1272407320',
                'email' => 'zoloxuhop@mailinator.com',
                'dob' => '1991-03-17',
                'address' => '{"value":"Est itaque enim cup"}',
                'base_measurements' => 'null',
                'notes' => '[{"label":"Voluptas ut odit pla","text":"Aut voluptatem Odio"}]',
                'created_at' => '2025-07-16 14:14:02',
                'updated_at' => '2025-07-16 14:14:02',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'name' => 'testcustomer2',
                'gender' => 'o',
                'country_code' => '+91',
                'phone' => '1311869678',
                'email' => 'cezeca@mailinator.com',
                'dob' => '1981-11-07',
                'address' => '{"value":"Enim consequat Expe"}',
                'base_measurements' => 'null',
                'notes' => '[{"label":"Architecto quidem al","text":"Veniam aute et inci"}]',
                'created_at' => '2025-07-16 14:14:24',
                'updated_at' => '2025-07-16 14:14:24',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'name' => 'testcustomer3',
                'gender' => 'o',
                'country_code' => '+91',
                'phone' => '1694364745',
                'email' => 'gymukawin@mailinator.com',
                'dob' => '2018-02-01',
                'address' => '{"value":"Incididunt eum autem"}',
                'base_measurements' => 'null',
                'notes' => '[{"label":"Et exercitation mole","text":"Enim irure et accusa"}]',
                'created_at' => '2025-07-16 14:14:41',
                'updated_at' => '2025-07-16 14:14:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}