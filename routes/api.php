<?php

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

Route::get('/profile/{profileCode}', 'ProfileController@show');
Route::post('/profile', 'ProfileController@store');
Route::put('/profile/{profileCode}', 'ProfileController@update');

Route::put('/photo/{profileCode}', 'PhotoController@update');
Route::get('/photo/{profileCode}', 'PhotoController@download');
Route::delete('/photo/{profileCode}', 'PhotoController@delete');
