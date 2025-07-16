<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('orders')->delete();
        
        \DB::table('orders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'customer_id' => 2,
                'order_number' => '000001',
                'status' => 'created',
                'total_amount' => '2700.00',
                'advance_paid' => '0.00',
                'delivery_date' => '2025-07-31',
                'close_date' => NULL,
                'notes' => '[{"label":null,"text":null}]',
                'created_at' => '2025-07-16 06:16:51',
                'updated_at' => '2025-07-16 06:16:51',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'customer_id' => 3,
                'order_number' => '000002',
                'status' => 'created',
                'total_amount' => '4000.00',
                'advance_paid' => '698.00',
                'delivery_date' => '2025-08-29',
                'close_date' => NULL,
                'notes' => '[{"label":null,"text":null}]',
                'created_at' => '2025-07-16 06:18:31',
                'updated_at' => '2025-07-16 06:18:31',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}