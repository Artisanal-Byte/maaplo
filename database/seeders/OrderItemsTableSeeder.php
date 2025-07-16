<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_items')->delete();
        
        \DB::table('order_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'template_id' => 2,
                'measurements' => '"{\\"length\\":\\"44\\",\\"front_neck\\":\\"44\\",\\"sleeve_length\\":\\"44\\",\\"waist\\":\\"44\\",\\"chest\\":\\"44\\",\\"shoulder\\":\\"44\\",\\"back_neck\\":\\"44\\"}"',
                'design_detail' => '"{\\"Front Neck\\":\\"4\\",\\"Back Part\\":\\"3\\"}"',
                'colors' => 'dark-red',
                'work_type' => 'New from Material',
                'material_type' => 'silk',
                'material_code' => 'abc#123123',
                'refrence_dress' => 'storage/user_name_sahil_id_1/customers/customer_id_2/orders_images/item_id_1/refrence_dress_1752646611.webp',
                'is_urgent' => false,
                'material_cost' => '500.00',
                'stiching_cost' => '2000.00',
                'altering_cost' => NULL,
                'item_cost' => '2500.00',
                'notes' => '[{"label":"abc","text":"abc"}]',
                'trial_dates' => '2025-07-21',
                'delivery_date' => '2025-07-31',
                'status' => 'created',
                'cloth_img1' => NULL,
                'cloth_img2' => NULL,
                'Pattern_img1' => NULL,
                'Pattern_img2' => NULL,
                'created_at' => '2025-07-16 06:16:51',
                'updated_at' => '2025-07-16 06:16:51',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 1,
                'template_id' => 3,
                'measurements' => '"{\\"length\\":\\"33\\",\\"front_neck\\":\\"33\\",\\"sleeve_circle\\":\\"33\\",\\"sleeve_length\\":\\"33\\",\\"arms\\":\\"33\\",\\"waist\\":\\"33\\",\\"shoulder\\":\\"33\\",\\"back_neck\\":\\"33\\"}"',
                'design_detail' => '"{\\"Front Neck\\":\\"7\\",\\"Back Part\\":\\"2\\"}"',
                'colors' => 'pink',
                'work_type' => 'Only Stitching',
                'material_type' => NULL,
                'material_code' => NULL,
                'refrence_dress' => NULL,
                'is_urgent' => true,
                'material_cost' => '0.00',
                'stiching_cost' => '200.00',
                'altering_cost' => NULL,
                'item_cost' => '200.00',
                'notes' => '[{"label":"asd","text":"asd"}]',
                'trial_dates' => '2025-07-19',
                'delivery_date' => '2025-08-22',
                'status' => 'created',
                'cloth_img1' => NULL,
                'cloth_img2' => NULL,
                'Pattern_img1' => NULL,
                'Pattern_img2' => NULL,
                'created_at' => '2025-07-16 06:16:51',
                'updated_at' => '2025-07-16 06:16:51',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_id' => 2,
                'template_id' => 3,
                'measurements' => '"{\\"length\\":34,\\"front_neck\\":34,\\"sleeve_circle\\":34,\\"sleeve_length\\":34,\\"arms\\":34,\\"waist\\":34,\\"shoulder\\":34,\\"back_neck\\":34}"',
                'design_detail' => '"{\\"Front Neck\\":4,\\"Back Part\\":5}"',
                'colors' => 'silver',
                'work_type' => 'Only Stitching',
                'material_type' => NULL,
                'material_code' => NULL,
                'refrence_dress' => NULL,
                'is_urgent' => false,
                'material_cost' => '0.00',
                'stiching_cost' => '4000.00',
                'altering_cost' => NULL,
                'item_cost' => '4000.00',
                'notes' => '[{"label":"avc","text":"avx"}]',
                'trial_dates' => '2025-07-21',
                'delivery_date' => '2025-07-31',
                'status' => 'created',
                'cloth_img1' => NULL,
                'cloth_img2' => NULL,
                'Pattern_img1' => NULL,
                'Pattern_img2' => NULL,
                'created_at' => '2025-07-16 06:18:31',
                'updated_at' => '2025-07-16 06:18:31',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}