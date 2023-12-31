<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/support', 'App\Http\Controllers\SupportController@index');
Route::get('/privacy_policy', 'App\Http\Controllers\PrivacyPolicyController@index');

Route::post('/support', 'App\Http\Controllers\SupportController@submit');
