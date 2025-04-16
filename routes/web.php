<?php

use App\Http\Controllers\RentRange;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/rent-range', [RentRange::class, 'getRentRange'])->withoutMiddleware('web');
