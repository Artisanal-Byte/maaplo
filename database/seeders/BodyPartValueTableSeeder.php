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
                // 'id' => 1,
                'body_part' => 'Front Neck',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
        ));


    }
}
