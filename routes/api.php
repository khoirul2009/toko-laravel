<?php

use App\Models\Order;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kategori', function () {
    return Category::with('products')->get();
});

Route::get('/test/{transaction:id}', function (Transaction $transaction) {
    $trasaksi = Transaction::with(['order', 'users'])->where('id', $transaction->id)->first();
    $produk = [];
    foreach ($trasaksi->order as $order) {
        array_push($produk, Order::with(['product'])->where('id', $order->id)->get());
    }
    return $produk;
});
