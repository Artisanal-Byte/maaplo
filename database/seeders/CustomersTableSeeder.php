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
                'name' => 'john doe',
                'gender' => 'm',
                'country_code' => '+91',
                'phone' => '1272407320',
                'email' => 'johndoe@mailinator.com',
                'dob' => '1991-03-17',
                'address' => '{"value":"1 E 2nd St, New York, NY 10003, USA"}',
                'base_measurements' => '{"unit":"cm","length":160,"front_neck":9,"sleeve_circle":6,"sleeve_length":10,"seat":32,"arms":5,"waist":36,"chest":33,"shoulder":3,"back_neck":9}',
                'notes' => '[{"label":"Voluptas ut odit pla","text":"Aut voluptatem Odio"}]',
                'created_at' => '2025-07-16 14:14:02',
                'updated_at' => '2025-07-18 04:12:56',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'name' => 'jane smith',
                'gender' => 'm',
                'country_code' => '+91',
                'phone' => '1311869678',
                'email' => 'janesmith@mailinator.com',
                'dob' => '1981-11-07',
                'address' => '{"value":"181 Mercer Street, New York, NY 10012, United States"}',
                'base_measurements' => '{"unit":"in","length":12,"front_neck":121,"sleeve_circle":12,"sleeve_length":12,"seat":12,"arms":121,"waist":21,"shoulder":21,"back_neck":21}',
                'notes' => '[{"label":"Architecto quidem al","text":"Veniam aute et inci"}]',
                'created_at' => '2025-07-16 14:14:24',
                'updated_at' => '2025-07-18 04:14:00',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'name' => 'chris parker',
                'gender' => 'o',
                'country_code' => '+91',
                'phone' => '1694364745',
                'email' => 'chrisparker@mailinator.com',
                'dob' => '2018-02-01',
                'address' => '{"value":"239 Greene St, New York, NY 10003, USA"}',
                'base_measurements' => '{"unit":"in","length":11,"front_neck":11,"sleeve_circle":11,"sleeve_length":11,"seat":11,"arms":11,"waist":11,"chest":11,"shoulder":11,"back_neck":11}',
                'notes' => '[{"label":"Et exercitation mole","text":"Enim irure et accusa"}]',
                'created_at' => '2025-07-16 14:14:41',
                'updated_at' => '2025-07-18 04:14:57',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}