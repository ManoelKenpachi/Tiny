<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
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

Route::get('/produtos', [ProdutoController::class, 'index']);

Route::get('/products', [ProdutoController::class, 'getlistProducts']);
Route::get('/product/{id}', [ProdutoController::class, 'getStockId']);

Route::post('/addProduct', [ProdutoController::class, 'postAddProduct']);

Route::put('/attProduct', [ProdutoController::class, 'putProductId']);
Route::put('/attStock', [ProdutoController::class, 'putStockId']);
