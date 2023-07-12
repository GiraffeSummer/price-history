<?php

namespace App\Console\Commands;

use App\Models\InventoryDetail;
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

    public function inRange($value, $min, $max)
    {
        return $value >= $min && $value <= $max;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->advanced();
    }


    public function advanced()
    {
        InventoryDetail::all()->map(function ($detail) {
            $dummyCreatedAt = Carbon::now();

            $action = rand(1, 10);
            $timeMod = rand(1, 50);
            $dummyCreatedAt = $dummyCreatedAt->addMinutes($timeMod);
            switch ($action) {
                case $this->inRange($action, 0, 6):
                    $mod = rand(-1000, 1000);
                    $detail->price = $detail->price + $mod;
                    $detail->save();

                    $this->components->info("[$dummyCreatedAt] (+$timeMod): " . $mod);
                    break;
                default:
                    //reset
                    $this->components->info("[$dummyCreatedAt] (+$timeMod): " . 'base / reset');
                    $detail->save();
                    break;
            }
        });
    }


    public function basic()
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
