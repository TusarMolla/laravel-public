<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
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



Route::controller(AuthController::class)->group(function(){
    Route::post("signup","signup");
    Route::post("signin","login");
    Route::post("logout","logout")->middleware('auth:sanctum');
    Route::get("info-by-token","infoByToken");

});



Route::middleware("auth:sanctum")->group(function(){

    Route::controller(ProductController::class)->group(function(){
        Route::post("product/add","store");
        Route::get("product/all","index")->withoutMiddleware("auth:sanctum");
        Route::get("product/details/{id}","details")->withoutMiddleware("auth:sanctum");
    });

    
    Route::controller(CartController::class)->group(function(){
        Route::post("cart/add","store");
        Route::get("cart/delted/{id}","delete");
    
    });
    Route::controller(OrderController::class)->group(function(){
        Route::post("order/all","index");
        Route::post("order/add","store");
    });


});





Route::fallback(function(){
    return response()->json([

        'data'=>[],
        'result'=>false,
        'status'=>404,
        'message'=>"invalid route"
    ]);
});

