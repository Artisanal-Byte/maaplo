<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        static $orderNumber = null;
        if (is_null($orderNumber)) {
            // Get max order_number, remove leading zeros, convert to int
            $lastOrderNumber = Order::max('order_number');

            if ($lastOrderNumber) {
                $orderNumber = (int) ltrim($lastOrderNumber, '0') + 1;
            } else {
                $orderNumber = 1;
            }
        }
        return [
            'user_id' => User::factory(),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'order_number' => str_pad($orderNumber++, 6, '0', STR_PAD_LEFT),
            'status' => $this->faker->randomElement([
                'created',
                'in_process',
                'closed',
                'processed',
                'delivered',
                'completed',
                'cancelled',
                'trial_done',
                'in_alteration',
                'ready_for_delivery',
            ]),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'advance_paid' => $this->faker->randomFloat(2, 0, 500),
            'delivery_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'close_date' => $this->faker->optional()->dateTimeBetween('+1 month', '+2 months'),
            'notes' => ['note1' => $this->faker->sentence()],
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create order with 3 order items automatically.
     */
    public function withOrderItems(int $count = 3)
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            OrderItem::factory()
                ->count($count)
                ->create(['order_id' => $order->id]);
        });
    }
}
