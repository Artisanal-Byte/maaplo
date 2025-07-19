<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubscriptionPlansTableSeeder::class,
            MeasurementSeeder::class,
            UsersTableSeeder::class,
            BodyPartValueTableSeeder::class,
            TemplatesTableSeeder::class,
            TemplatesMeasurementsTableSeeder::class,
            DesignDetailsTableSeeder::class,
        ]);

        // \App\Models\DesignDetail::factory()->count(5)->create();

        $customers = Customer::factory()->count(4)->create();

        // Link each customer to user_id = 1 in users_customers table
        $customers->each(function ($customer) {
            \App\Models\UserCustomer::factory()->create([
                'customer_id' => $customer->id,
                // 'user_id' => 1,
            ]);
        });
        Order::factory()
            ->count(5)
            ->create([
                'user_id' => 1,   // yahan fix user_id 1 set kar diya
            ])
            ->each(function ($order) {
                OrderItem::factory()->count(3)->create([
                    'order_id' => $order->id,
                    // order items me bhi user_id 1
                ]);
            });
    }
}
