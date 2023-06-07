<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Console\Command;

class GeneratePriceHistory extends Command
{
    use safeLogs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-price-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan for changes in price history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // just the basics
        // need improvement for thousands of products
        // (chunking + workers)
        Product::all()->map(function ($product) {
            //needs startofday or endofday
            //fix: genereert altijd nieuwe => hoort: alleen nieuwe te genereren als vorige ouder is als $x (6 uur)
            $history = PriceHistory::where('product_id', '=', $product->id)
                ->where('created_at', '>=', Carbon::now()->subHours(6))
                ->firstOrNew([
                    'product_id' => $product->id,
                    'price' => $product->price
                ]);

            if ($history->exists) {
                $this->safeInfo("Updated price:" . $history->product_id . " > " . $history->price);
            } else {
                dump($history);
                $this->safeError("No update:" . $history->product_id . " > " . $history->price);
            }

            return $product;
        });
    }
}

trait safeLogs
{
    //adds safe logging features that echoes to html if component->[logfunction] isn't available
    protected function safeError($ctx)
    {
        try {
            $this->components->error($ctx);
        } catch (\Throwable $th) {
            echo "<div style='background-color:#eb3941'>$ctx</div>";
        }
    }

    protected function safeInfo($ctx)
    {
        try {
            $this->components->info($ctx);
        } catch (\Throwable $th) {
            echo "<div style='background-color:#009de0'>$ctx</div>";
        }
    }

    protected function safeWarn($ctx)
    {
        try {
            $this->components->warn($ctx);
        } catch (\Throwable $th) {
            echo "<div style='background-color:#f29900'>$ctx</div>";
        }
    }
}
