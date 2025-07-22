<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BodyPartValueTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('body_part_value')->delete();

        \DB::table('body_part_value')->insert(array (
            0 =>
            array (
                'body_part' => 'Front Neck',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'back Neck',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'fornt bottom',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'back bottom',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'bottom length',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
             array (
                'body_part' => 'ankle height',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'slive type',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'waist',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
            array (
                'body_part' => 'pocket style',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),

            array (
                'body_part' => 'fit',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
        ));


    }
}
