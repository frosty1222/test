<?php

use App\Http\Controllers\AuthController;
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
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/get-product-list',function(Request $request){
            $key = 'get-product-list';
            return CacheApi::cacheApiResponse($key, function () use ($request) {
                return app(ProductController::class)->getAll($request);
            });
        });
        Route::post('/create-product',[ProductController::class,'createProduct']);
        Route::post('/edit-product',[ProductController::class,'editProduct']);
        Route::delete('/delete-product',[ProductController::class,'deleteProduct']);
        Route::get('/check-name',[ProductController::class,'checkName']);
    });
});

Route::prefix('category')->group(function(){
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/get-category-list',function(Request $request){
            $key = 'get-category-list';
            return CacheApi::cacheApiResponse($key, function () use ($request) {
                return app(CategoryController::class)->getAll($request);
            });
        });
        Route::post('/create-category',[CategoryController::class,'createCate']);
        Route::post('/edit-category',[CategoryController::class,'editCate']);
        Route::delete('/delete-category',[CategoryController::class,'deleteCate']);
        Route::get('/check-name',[CategoryController::class,'checkName']);
    });
});
Route::prefix('auth')->group(function(){
   Route::post('/login',[AuthController::class,'postLogin']);
   Route::post('/register',[AuthController::class,'postRegister']);
   Route::post('/logout',[AuthController::class,'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
