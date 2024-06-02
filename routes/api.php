<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('product')->group(function(){
    Route::get('/get-product-list',[ProductController::class,'getAll']);
    Route::post('/create-product',[ProductController::class,'createProduct']);
    Route::put('/edit-product-list',[ProductController::class,'editProduct']);
    Route::delete('/delete-product',[ProductController::class,'deleteProduct']);
    Route::get('/check-name',[ProductController::class,'checkName']);
});
Route::prefix('category')->group(function(){
    Route::get('/get-category-list',[CategoryController::class,'getAll']);
    Route::post('/create-category',[CategoryController::class,'createCate']);
    Route::put('/edit-category-list',[CategoryController::class,'editCate']);
    Route::delete('/delete-category',[CategoryController::class,'deleteCate']);
    Route::get('/check-name',[CategoryController::class,'checkName']);
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
