<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->create([
            'nama'  => 'Komputer',
            'slug'  => 'komputer',
            'icon'  => 'komputer'
        ]);
        Category::factory()->create([
            'nama'  => 'Gadget',
            'slug'  => 'gadget',
            'icon'  => 'gadget'
        ]);
        Category::factory()->create([
            'nama'  => 'Fashion',
            'slug'  => 'fashion',
            'icon'  => 'fashion'
        ]);
    }
}
