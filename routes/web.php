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

Route::get('/', function () {
    return view('photographers.index');
})->name('index');

Route::get('/show', function () {
    return view('photographers.show');
}) ->name('show');
Route::get('/show/booking', function () {
    return view('photographers.booking');
}) ->name('booking');