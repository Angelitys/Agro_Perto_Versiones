<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Tomates Orgânicos', 'Bananas Prata', 'Cenouras Baby', 'Alface Crespa',
            'Maçãs Fuji', 'Batatas Doces', 'Abóbora Cabotiá', 'Pimentões Vermelhos',
            'Laranjas Lima', 'Brócolis Frescos', 'Cebolas Roxas', 'Milho Verde'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . $this->faker->randomNumber(3)),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 2.50, 25.00),
            'stock_quantity' => $this->faker->numberBetween(5, 100),
            'unit' => $this->faker->randomElement(['kg', 'unidade', 'maço', 'dúzia']),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'active' => true,
            'origin' => $this->faker->city() . ', ' . $this->faker->stateAbbr(),
            'harvest_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'images' => null,
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }
}
