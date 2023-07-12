<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\Product::factory(60)
            ->recycle(\App\Models\Supplier::factory(3)->create())
            ->has(
                \App\Models\InventoryDetail::factory()
                    ->count(rand(0, 3))
                    ->state(function (array $attributes, $product){
                        return ['supplier_id'=>rand(1,3)];
                    })
            )
            ->create();

        // \App\Models\Product::factory(60)
        //     // ->withInventoryDetails(rand(0, 3))
        //     ->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
