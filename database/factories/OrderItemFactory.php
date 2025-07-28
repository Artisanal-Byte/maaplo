<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $frontNeckOptions = [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 14, 15];
        $backNeckOptions = [6, 13, 16, 17, 18];
        return [
            'order_id' => null,
            'template_id' => Template::inRandomOrder()->first()->id,
            // 'item_template_id' => null, // ✅ REMOVE THIS LINE
            // 'name' => $this->faker->word(),
            'measurements' => json_encode([]),
            'design_detail' => json_encode([
                'Front Neck' => $this->faker->randomElement($frontNeckOptions),
                'Back Neck' => $this->faker->randomElement($backNeckOptions),
            ]),
            'colors' => $this->faker->colorName(),
            'material_type' => $this->faker->word(),
            'trial_dates' => $this->faker->date(),
            // 'price' => $this->faker->randomFloat(2, 10, 500),
            'status' => 'created',
            'is_urgent' => $this->faker->boolean(10),
            'item_cost' => $this->faker->randomFloat(2, 5, 200),
            'material_code' => $this->faker->word(),
            'material_cost' => $this->faker->randomFloat(2, 5, 200),
            'material_type' => $this->faker->word(),
            'notes' => json_encode([]),
            'refrence_dress' => null,
            'stiching_cost' => $this->faker->randomFloat(2, 0, 50),
            'altering_cost' => $this->faker->randomFloat(2, 0, 50),
            'delivery_date' => $this->faker->date(),
            'work_type' => $this->faker->word(),
            'cloth_img1' => null,
            'cloth_img2' => null,
            'Pattern_img1' => null,
            'Pattern_img2' => null,
        ];
    }
}
