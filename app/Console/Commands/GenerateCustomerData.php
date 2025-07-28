<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserCustomer;

class GenerateCustomerData extends Command
{
    protected $signature = 'generate:customer-data';
    protected $description = 'Generate customers with orders and order items';

    public function handle()
    {
        $count = (int) $this->ask('How many customers do you want to create?');

        if ($count <= 0) {
            $this->error('Please enter a number greater than 0.');
            return;
        }

        $this->info("Generating $count customers...");

        \DB::transaction(function () use ($count) {
            $customer = Customer::factory()
                ->count($count)
                ->create();
            foreach ($customer as $customer) {
                UserCustomer::create([
                    'user_id' => 1,
                    'customer_id' => $customer->id,
                ]);
                Order::factory()
                    ->count(2)
                    ->create(['customer_id' => $customer->id, 'user_id' => 1])
                    ->each(function ($order) {
                        OrderItem::factory()
                            ->count(3)
                            ->create(['order_id' => $order->id]);
                    });
            }
        });
        $this->info("Successfully generated $count customers with related orders and items.");
    }
}
