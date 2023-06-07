<?php

namespace App\Console\Commands;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRandomPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-random-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomly change prices on all products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Product::all()->map(function ($product) {
            $timeMod = rand(1, 50);
            $dummyCreatedAt = Carbon::now()->addMinutes($timeMod);
            $mod = rand(-1000, 1000);

            $this->components->info("[$dummyCreatedAt] (+$timeMod): #" . $product->id . " Mod: " . $mod . " = " . $product->price + $mod);
            return $product->update([
                'price' => $product->price + $mod,
                'created_at' => $dummyCreatedAt
            ]);
        });
    }
}
