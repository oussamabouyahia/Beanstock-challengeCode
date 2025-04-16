<?php

use App\Http\Controllers\RentRange;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//I ignored the web middleware for this route to avoid csrf error as I don't work witha full auth system
Route::post('/rent-range', [RentRange::class, 'getRentRange'])->name("rent-range")->withoutMiddleware('web');
