<?php

namespace App\Console\Commands;

use App\Models\InventoryDetail;
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
        InventoryDetail::all()->map(function (InventoryDetail $detail) {
            //needs startofday or endofday
            //fix: genereert altijd nieuwe => hoort: alleen nieuwe te genereren als vorige ouder is als $x (6 uur)
            $relatedHistory = PriceHistory::where('product_id', '=', $detail->product_id);

            //states:
            // no update
            // new
            // update

            if ($relatedHistory->count() < 1) {
                $this->safeInfo("New Entry:" .  $detail->product_id);
                $history = $relatedHistory
                    ->firstOrCreate(
                        [
                            'product_id' => $detail->product_id,
                            'supplier_id' => $detail->supplier_id,
                            'price' => $detail->price
                        ],
                    );
            } else {
                $history = $relatedHistory
                    ->whereDate('created_at', '>=', Carbon::now()->subHours(6))
                    ->firstOrNew([
                        'product_id' => $detail->product_id,
                        'supplier_id' => $detail->supplier_id,
                        'price' => $detail->price
                    ]);


                // dump($history);
                if (!$history->exists) {
                    $this->safeInfo("Updated price:" . $history->product_id . " > " . $history->price);
                    $history->save();
                } else {
                    $this->safeError("No update:" . $history->product_id . " > " . $history->price);
                }
            }

            return $detail;
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
