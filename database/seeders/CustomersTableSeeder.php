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

        \DB::table('customers')->insert(array(
            0 =>
            array(
                'id' => 1,
                'user_id' => 1,
                'name' => 'testcustomer2',
                'gender' => 'm',
                'country_code' => '+91',
                'phone' => '7622912910',
                'email' => 'customer@gmail.com',
                'dob' => '1999-05-25',
                'address' => '{"value":"96,gujarat housing board, somnath road, mehsana"}',
                'base_measurements' => '{"unit":"in","length":"10","back_neck":"10","sleeve_circle":"10","sleeve_length":"10","seat":"10","arms":"10","waist":"10","chest":"10","shoulder":"10","front_neck":"10"}',
                'notes' => '[{"label":"Test Label 1","text":"Brijesh"}]',
                'created_at' => '2025-06-12 10:11:08',
                'updated_at' => '2025-07-16 06:13:21',
                'deleted_at' => NULL,
            ),
            1 =>
            array(
                'id' => 2,
                'user_id' => 1,
                'name' => 'testcustomer1',
                'gender' => 'f',
                'country_code' => '+91',
                'phone' => '1871668752',
                'email' => 'zedifi@mailinator.com',
                'dob' => '1988-02-16',
                'address' => '{"value":"Iusto voluptate quis"}',
                'base_measurements' => '{"unit":"in","length":"11","front_neck":"11","sleeve_length":"11","arms":"11","chest":"11","back_neck":"11","sleeve_circle":"11","seat":"11","waist":"11","shoulder":"11"}',
                'notes' => '[{"label":"Voluptatem aliquid","text":"Excepturi minima ea"}]',
                'created_at' => '2025-07-16 06:12:59',
                'updated_at' => '2025-07-16 06:12:59',
                'deleted_at' => NULL,
            ),
            2 =>
            array(
                'id' => 3,
                'user_id' => 1,
                'name' => 'testcustomer3',
                'gender' => 'm',
                'country_code' => '+91',
                'phone' => '1143299772',
                'email' => 'fovyv@mailinator.com',
                'dob' => '2013-02-10',
                'address' => '{"value":"Amet dolor consequa"}',
                'base_measurements' => '{"unit":"in","length":"22","front_neck":"232","sleeve_circle":"22","seat":"22","waist":"22","shoulder":"22","sleeve_length":"22","arms":"22","chest":"22","back_neck":"22"}',
                'notes' => '[{"label":"Eiusmod sed error sa","text":"Doloribus nesciunt"}]',
                'created_at' => '2025-07-16 06:14:07',
                'updated_at' => '2025-07-16 06:14:07',
                'deleted_at' => NULL,
            ),
        ));
    }
}
