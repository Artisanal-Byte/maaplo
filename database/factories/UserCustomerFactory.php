<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\UserCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserCustomer>
 */
class UserCustomerFactory extends Factory
{
    protected $model = UserCustomer::class;

    public function definition(): array
    {
        return [
            'user_id' => 1,
            'customer_id' => Customer::inRandomOrder()->first()->id, // picks one of the existing 3 customers
        ];
    }
}
