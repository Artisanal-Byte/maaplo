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
                'name' => 'customer',
                'gender' => 'm',
                'country_code' => '+91',
                'phone' => '7622912910',
                'email' => 'customer@gmail.com',
                'dob' => '1999-05-25',
                'address' => '{"value":"96,gujarat housing board, somnath road, mehsana"}',
                'base_measurements' => '{"unit":"in","length":"10","back_neck":"10","sleeve_circle":"10","sleeve_length":"10","seat":"10","arms":"10","waist":"10","chest":"10","shoulder":"10","front_neck":"10"}',
                'notes' => '[{"label":"Test Label 1","text":"Brijesh"}]',
                'created_at' => '2025-06-12 10:11:08',
                'updated_at' => '2025-06-12 10:11:08',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}