<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DesignDetailsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('design_details')->delete();

        \DB::table('design_details')->insert(array (
            0 =>
            array (
                // 'id' => 1,
                'body_section' => 'Upper',
                'gender' => 'm',
                'body_part_id' => 1,
                'value' => 'u_neck',
                'image' => '<svg width="50" height="50" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
xmlns:xlink="http://www.w3.org/1999/xlink">
<mask id="mask0_515_950" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="16"
height="16">
<rect width="16" height="16" transform="matrix(-1 0 0 1 16 0)" fill="url(#pattern0_515_950)" />
</mask>
<g mask="url(#mask0_515_950)">
<rect width="32" height="37" transform="matrix(-1 0 0 1 22 -8)" fill="#167893" />
</g>
<defs>
<pattern id="pattern0_515_950" patternContentUnits="objectBoundingBox" width="1" height="1">
<use xlink:href="#image0_515_950" transform="scale(0.0078125)" />
</pattern>
<image id="image0_515_950" width="128" height="128" preserveAspectRatio="none"
xlink:href="data:image/png;base64,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" />
</defs>
</svg>',
                'created_at' => '2025-06-12 10:09:04',
                'updated_at' => '2025-06-12 10:09:04',
                'deleted_at' => NULL,
            ),
        ));


    }
}
