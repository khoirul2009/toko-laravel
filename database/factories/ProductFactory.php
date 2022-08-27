<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'nama'  => $this->faker->sentence(3),
            'slug'  => $this->faker->slug(3),
            'id_kategori' => $this->faker->numberBetween(1, 3),
            'berat' => $this->faker->numberBetween(1000, 2000),
            'harga' => $this->faker->numberBetween(1000, 1000000),
            'stok'  => $this->faker->numberBetween(10, 20),
            'gambar' => 'products-images/default.jpg',
            'deskripsi' => $this->faker->paragraph(4)

        ];
    }
}
