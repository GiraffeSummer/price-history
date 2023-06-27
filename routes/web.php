<?php

use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Support\Facades\Route;
use App\Console\Commands\GeneratePriceHistory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// return view('welcome');

Route::get('/', function () {
    return 'done';
});
Route::get('/test', function () {
    (new GeneratePriceHistory())->handle();
    return collapseAll();
});

Route::get('/history', function () {
    return Product::with('prices')->get();
});

Route::get('/history/{product:id}/raw', function (Product $product) {
    $product = $product;
    $history = $product->prices()->orderBy('created_at')->get();

    dump($product, $history);

    //expand all collapsable
    return collapseAll();
});

Route::get('/history/{product:id}', function (Product $product) {

    $product = $product;
    $history = $product->prices()->orderBy('created_at')->get();

    return view('chart', compact(['history', 'product']));
});


function collapseAll()
{
    return "<script>
        //expand all collapsable
        [...document.querySelectorAll('.sf-dump-toggle')].map(x=>{
         if(x.nextSibling.classList.contains('sf-dump-compact')){
               x.click()
         }
        })
    </script>";
}
