<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TemplatesMeasurementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('templates_measurements')->delete();

        \DB::table('templates_measurements')->insert(array (
            0 =>
            array (
                // 'id' => 1,
                'template_id' => 1,
                'measurements_id' => 10,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
