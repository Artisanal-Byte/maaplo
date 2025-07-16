<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscription_plans')->insert([
            [
                // 'id' => 1,
                'plan_title' => 'free',
                'plan_description' => 'Basic features suitable for individuals and small teams.',
                'plan_price' => 00,
                'plan_currency' => 'INR',
                'features' => json_encode(['5 Customer', 'Unlimited orders', 'Basic Support']),
                'visibility' => true,
                'user_limit' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]

        ]);
    }
}
