<?php

namespace App\Console\Commands;

use App\Models\PriceHistory;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRandomHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generaterandomhistory {product} {entries=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Randomly add price history to selected product';

    public function inRange($value, $min, $max)
    {
        return $value >= $min && $value <= $max;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->argument('product');
        $entries = $this->argument('entries');
        $product = Product::find($productId);
        if (!$product->exists) {
            return $this->components->error('Product does not exist');
        }
        $basePrice = $product->price;

        $dummyCreatedAt = Carbon::now();

        for ($i = 0; $i < $entries; $i++) {
            $action = rand(1, 10);
            $timeMod = rand(1, 50);
            $dummyCreatedAt = $dummyCreatedAt->addMinutes($timeMod);
            switch ($action) {
                case $this->inRange($action, 0, 8):
                    $mod = rand(-1000, 1000);
                    PriceHistory::create([
                        'product_id' => $product->id,
                        'price' => $basePrice + $mod,
                        'created_at' => $dummyCreatedAt
                    ]);
                    $this->components->info("[$dummyCreatedAt] (+$timeMod): " . $mod);
                    break;
                default:
                    //reset
                    $this->components->info("[$dummyCreatedAt] (+$timeMod): " . 'base / reset');
                    PriceHistory::create([
                        'product_id' => $product->id,
                        'price' => $basePrice,
                        'created_at' => $dummyCreatedAt
                    ]);
                    break;
            }
        }
    }
}
