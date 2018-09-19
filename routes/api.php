<?php

use Illuminate\Http\Request;

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

Route::middleware(\App\Http\Middleware\AddJsonAcceptHeader::class)->group(function () {
    $this->post('authenticate','AuthenticateController@authenticate');
    $this->post('fresh-tokens','AuthenticateController@freshTokens');
});


Route::middleware(Tymon\JWTAuth\Middleware\GetUserFromToken::class)->group(function () {
    $this->get('payment-dates','PaymentController@paymentDates');
});
