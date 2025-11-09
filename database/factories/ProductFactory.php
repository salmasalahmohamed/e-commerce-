<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        protected $model = Product::class;

        public function definition()
    {
        $name = $this->faker->word;

        return [
            'category_id' => Category::factory(),
            'brand_id'    => Brand::factory(),
            'name'        => $name,
            'slug'        => Str::slug($name) . '-' . Str::random(4),
            'image' => [$this->faker->imageUrl()],
            'description' => $this->faker->paragraph,
            'price'       => $this->faker->randomFloat(2, 50, 5000),
            'is_active'   => true,
            'is_featured' => false,
            'is_stock'    => true,
            'is_sale'     => false,
        ];
    }

}
